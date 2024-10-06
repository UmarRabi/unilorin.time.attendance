<?php

namespace App\Http\Controllers\Tenant\Employee;

use App\Helpers\Traits\DepartmentAuthentications;
use App\Http\Controllers\Controller;
use App\Manager\Employee\EmployeeManager;
use App\Models\Core\Auth\Profile;
use App\Models\Core\Auth\User;
use App\Models\Tenant\Attendance\Attendance;
use App\Models\Tenant\Attendance\AttendanceDetails;
use App\Repositories\Core\Status\StatusRepository;
use App\Services\Tenant\Attendance\AttendanceService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ManualAttendanceController extends Controller
{
    use DepartmentAuthentications;

    public function __construct(AttendanceService $service)
    {
        $this->service = $service;
    }

    public function store(Request $request)
    {
        $this->departmentAuthentications($request->get('employee_id'), true);

        $this->service
            ->setAttributes(
                array_merge($request->only('employee_id', 'in_date', 'note', 'in_time', 'out_time'), [
                    'review_by' => auth()->id(),
                ])
                );
            // ->validateManual()
            // ->validateIfNotFuture();

        $employee = User::find($request->get('employee_id'));

        $this->service->setModel($employee);

        $status_name = $this->service->autoApproval() ? 'approve' : 'pending';
        $status_methods = 'attendance' . ucfirst($status_name);
        $status = resolve(StatusRepository::class)->$status_methods();

        DB::transaction(function () use ($employee, $status_name, $status) {
            $this->service
                ->setAttr('status_id', $status)
                ->setAttr('note_type', $status_name == 'approve' ? 'manual' : 'request')
                ->when(
                    $status_name == 'approve',
                    fn(AttendanceService $service) => $service->setAttr('review_by', auth()->id())
                )->setAttr('added_by', auth()->id())
                ->manualAddPunch()
                ->when(
                    $status_name == 'approve',
                    function (AttendanceService $service) use ($status) {
                        $attributes = ['status_id' => $status];

                        if (!$service->isNotFirstAttendance()) {
                            $attributes = array_merge([
                                'behavior' => $service->getUpdateBehavior()
                            ], $attributes);
                        }

                        $service->updateAttendance($attributes);
                    }
                );
        });

        return response()->json([
            'status' => true,
            'message' => __('default.added_response', ['subject' => __t('attendance'), 'object' => $employee->full_name])
        ]);
    }

    public function webhook(Request $request)
    {

        // Log::alert($request);
        try {
            Log::alert($request);
            $prefixes = [
                '1' => 'J',
                '2' => 'S'
            ];
            $employee = $request->records[0]['employee'];
            $device = $request->records[0]['device'];

            $cader = substr($employee['workno'], 0, 1);
            $in_time = $request->records[0]['check_time'];
            $in_time = Carbon::parse($in_time);
            $in_date = $in_time->toDateString();
            $employee['workno'] = $prefixes[$cader] . substr($employee['workno'], 1);
            $profile = Profile::where('employee_id', $employee['workno'])
                ->select('user_id')
                ->first();

            $previousAttendance = $this->service->hasTodayAttendance($in_date, $profile->user_id);

            if ($previousAttendance == null) {
                $attendance = Attendance::create(
                    [
                        'in_date' => $in_date,
                        'user_id' => $profile->user_id,
                        'status_id' => 7,
                        'working_shift_id' => 1,
                        'tenant_id' => 1,
                        'behavior' => 'regular'
                    ]
                );

                AttendanceDetails::create([
                    'in_time' => $in_time,
                    'attendance_id' => $attendance->id,
                    'status_id' => 7,
                    'added_by' => 1,
                    'review_by' => 1,
                    'device_name'=> $device['serial_number'],
                    'device_serial_number'=>$device['name']
                ]);
            } else {
                AttendanceDetails::where('attendance_id', $previousAttendance->id)
                    ->update(
                        [
                            'out_time' => $in_time
                        ]
                    );
            }
            // return $emploeyService->findByEmployeeId($employee['workno']);

            // $this->departmentAuthentications($employee['workno'], true);


        } catch (\Exception $e) {
            Log::alert($e);
            // return $e;
        }
    }
}
