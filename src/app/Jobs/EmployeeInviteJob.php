<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Exceptions\GeneralException;
use App\Hooks\User\AfterUserConfirmed;
use App\Hooks\User\AfterUserInvited;
use App\Hooks\User\BeforeUserInvited;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Employee\EmployeeRequest;
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

class EmployeeInviteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected AfterUserInvited $afterUserInvited;

    protected BeforeUserInvited $beforeUserInvited;

    protected EmployeeInviteService $service;

    protected Request $request;
    public function __construct(
        EmployeeInviteService $service, 
        BeforeUserInvited $beforeUserInvited, 
        AfterUserInvited $afterUserInvited, 
        Request $request
        )
    {
        $this->service = $service;
        $this->afterUserInvited = $afterUserInvited;
        $this->beforeUserInvited = $beforeUserInvited;
        $this->request= $request;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        DB::beginTransaction();
        try {
                $departmentId = null;
                $designationId = null;
                $department = Department::where('name', $this->request->department)
                    ->first();
                if ($department == null) {
                    $department=Department::first();
                    // return response()->json([
                    //     'status'=>false,
                    //     'message'=>'Invalid Department'
                    // ]);
                }

                if(Profile::where('employee_id', $this->request->employee_id)->exists()){
                    Log::alert($this->request->employee_id." already exist");
                    return response()->json([
                        'status'=>false,
                        'message'=>'Employee already registered'
                    ]);
                }
                $departmentId = $department->id;
                $designation = Designation::where('name', $this->request->designation)
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
                    'email' => $this->request->email,
                    'department_id' => $departmentId,
                    'designation_id' => $designationId,
                    'gender' => $this->request->gender,
                    'employee_id' => $this->request->employee_id,
                    'first_name' => $this->request->first_name,
                    'last_name' => $this->request->last_name,
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

                    
                    // $this->afterUserInvited
                    // ->setModel($user)
                    // ->cacheQueueClear();
                    
                    // AfterUserConfirmed::new()
                    // ->setModel($user)
                    // ->handle();
                    DB::commit();
            Log::alert($this->request->employee_id."record created successfully");
            // return response()->json([
            //     'status' => true,
            //     'message' => trans('default.create_employee_response')
            // ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::Alert($this->request->employee_id.$e->getMessage());
            // return $e->getMessage();
        }
    }
}
