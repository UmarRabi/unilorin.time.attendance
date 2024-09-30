<?php


namespace App\Http\Controllers\Tenant\Employee;


use App\Exceptions\GeneralException;
use App\Hooks\User\AfterUserConfirmed;
use App\Hooks\User\AfterUserInvited;
use App\Hooks\User\BeforeUserInvited;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Employee\EmployeeRequest;
use App\Jobs\EmployeeInviteJob;
use App\Mail\Tenant\EmployeeInvitationCancelMail;
use App\Models\Core\Auth\Profile;
use App\Models\Core\Auth\Role;
use App\Models\Core\Auth\User;
use App\Models\Tenant\Employee\Department;
use App\Models\Tenant\Employee\Designation;
use App\Notifications\Core\User\UserInvitationNotification;
use App\Services\Tenant\Employee\DepartmentService;
use App\Services\Tenant\Employee\EmployeeInviteService;
use App\Services\Tenant\Employee\EmployeeService;
// use AWS\CRT\HTTP\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmployeeInviteController extends Controller
{
    protected AfterUserInvited $afterUserInvited;

    protected BeforeUserInvited $beforeUserInvited;

    public function __construct(EmployeeInviteService $service, BeforeUserInvited $beforeUserInvited, AfterUserInvited $afterUserInvited)
    {
        $this->service = $service;
        $this->afterUserInvited = $afterUserInvited;
        $this->beforeUserInvited = $beforeUserInvited;
    }

    public function invite(EmployeeRequest $request)
    {
        DB::transaction(function () use ($request) {
            $this->beforeUserInvited
                ->handle();

            $user = $this->service
                ->setAttributes($request->except('allowed_resource', 'tenant_id', 'tenant_short_name'))
                ->when(!auth()->user()->can('attach_users_to_roles'), function (EmployeeInviteService $service) {
                    $employeeRoleId = Role::query()
                        ->where('alias', 'employee')
                        ->get()
                        ->pluck('id')
                        ->toArray();
                    $service->setAttr('roles', $employeeRoleId);
                })->validateRoles()
                ->validateMailSettings()
                ->invite();

            $this->afterUserInvited
                ->setModel($user)
                ->cacheQueueClear();

            notify()
                ->on('employee_invited')
                ->with($user)
                ->send(UserInvitationNotification::class);

            $this->afterUserInvited
                ->setModel($user)
                ->handle();
        });

        return response()->json([
            'status' => true,
            'message' => trans('default.invite_employee_response')
        ]);
    }

    public function create(EmployeeRequest $request)
    {
        DB::transaction(function () use ($request) {
            $this->beforeUserInvited
                ->handle();

            $user = $this->service
                ->setAttributes($request->except('allowed_resource', 'tenant_id', 'tenant_short_name'))
                ->when(!auth()->user()->can('attach_users_to_roles'), function (EmployeeInviteService $service) {
                    $employeeRoleId = Role::query()
                        ->where('alias', 'employee')
                        ->get()
                        ->pluck('id')
                        ->toArray();
                    $service->setAttr('roles', $employeeRoleId);
                })->validateRoles()
                ->validateMailSettings()
                ->create();

            $this->afterUserInvited
                ->setModel($user)
                ->cacheQueueClear();

            AfterUserConfirmed::new()
                ->setModel($user)
                ->handle();
        });

        return response()->json([
            'status' => true,
            'message' => trans('default.create_employee_response')
        ]);
    }
    public function createApi(Request $request)
    {
        // DB::beginTransaction();
        try {
            EmployeeInviteJob::dispatchAfterResponse($this->service, $this->beforeUserInvited, $this->afterUserInvited, $request);
            // Log::alert($request->employee_id."record created successfully");
            return response()->json([
                'status' => true,
                'message' => trans('default.create_employee_response')
            ]);
                $departmentId = null;
                $designationId = null;
                $department = Department::where('name', $request->department)
                    ->first();
                if ($department == null) {
                    $department=Department::first();
                    // return response()->json([
                    //     'status'=>false,
                    //     'message'=>'Invalid Department'
                    // ]);
                }

                if(Profile::where('employee_id', $request->employee_id)->exists()){
                    Log::alert($request->employee_id." already exist");
                    return response()->json([
                        'status'=>false,
                        'message'=>'Employee already registered'
                    ]);
                }
                $departmentId = $department->id;
                $designation = Designation::where('name', $request->designation)
                    ->first();
                if ($designation == null) {
                    $designation=Designation::first();
                    // return response()->json([
                    //     'status'=>false,
                    //     'message'=>'Invalid designation'
                    // ]);
                }
                $designationId = $designation->id;

                $this->beforeUserInvited
                    ->handle();
                $payload = [
                    'email' => $request->email,
                    'department_id' => $departmentId,
                    'designation_id' => $designationId,
                    'gender' => $request->gender,
                    'employee_id' => $request->employee_id,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'employment_status_id' => 2,
                    'work_shift_id' => 1
                ];
                $employeeRoleId = Role::query()
                    ->where('alias', 'employee')
                    ->get()
                    ->pluck('id')
                    ->toArray();
              
                    // $this->service
                    // ->setAttributes($payload);
                    // $this->service->setAttr('roles', $employeeRoleId);
                // return $this->service->getAttributes();
                $user = $this->service
                    ->setAttributes($payload)
                    ->setAttr('roles', $employeeRoleId)
                    ->validateRoles()
                    ->validateMailSettings()
                    ->create();


                $this->afterUserInvited
                    ->setModel($user)
                    ->cacheQueueClear();

                AfterUserConfirmed::new()
                    ->setModel($user)
                    ->handle();
            DB::commit();
            Log::alert($request->employee_id."record created successfully");
            return response()->json([
                'status' => true,
                'message' => trans('default.create_employee_response')
            ]);
        } catch (\Exception $e) {
            // DB::rollBack();
            Log::Alert($request->employee_id.$e->getMessage());
            return $e->getMessage();
        }
    }

    public function cancel(User $employee)
    {
        throw_if(
            !$employee->isInvited(),
            new GeneralException(__t('action_not_allowed'))
        );

        DB::transaction(function () use ($employee) {
            $this->service
                ->setModel($employee)
                ->cancel();

            Mail::to($employee->email)
                ->send((new EmployeeInvitationCancelMail((object)$employee->toArray()))->delay(5));
        });

        return response()->json(['status' => true, 'message' => __t('employee_invitation_canceled_successfully')]);
    }
}
