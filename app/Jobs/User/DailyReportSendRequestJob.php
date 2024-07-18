<?php

namespace App\Jobs\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use App\Models\StaffRequest\StaffRequest;
use App\Models\User\User;
use Spatie\Permission\Models\Role;
use App\Models\LetterAssignment\LetterAssignment;
use App\Notifications\RequestRegisteredNotification;

class DailyReportSendRequestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */

    public function __construct(protected $data)
    {
    }

    public function handle(): void
    {
        try {
            DB::transaction(function () {
                $requiredKeys = ['last_name', 'first_name', 'userId', 'email', 'mobile_phone', 'subject', 'description', 'organization', 'cod_staff', 'vacation_day', 'departmentRole', 'assigned_to'];
                foreach ($requiredKeys as $key) {
                    if (!array_key_exists($key, $this->data)) {
                        throw new \Exception("Undefined array key \"$key\"");
                    }
                }

                $staffRequest = new StaffRequest();
                $staffRequest->name = $this->data['last_name'] . ' ' . $this->data['first_name'];
                $staffRequest->user_id = $this->data['userId'];
                $staffRequest->email = $this->data['email'];
                $staffRequest->mobile_phone = $this->data['mobile_phone'];
                $staffRequest->subject = $this->data['subject'];
                $staffRequest->description = $this->data['description'];
                $staffRequest->organization = $this->data['organization'];
                $staffRequest->cod_staff = $this->data['cod_staff'];
                $staffRequest->vacation_day = $this->data['vacation_day'];
                $staffRequest->save();

                $role = Role::query()
                    ->select(['id', 'name'])
                    ->where('name', $this->data['departmentRole'])
                    ->firstOrFail();
                
                $assignment = new LetterAssignment();
                $assignment->user_id = $this->data['userId'];
                $assignment->request_id = $staffRequest->id;
                $assignment->role_id = $role->id;
                $assignment->assigned_to = $this->data['assigned_to'];
                $assignment->status = "waiting";
                $assignment->save();

                $user = User::whereHas('employee', function ($query) {
                    $query->where('staff_code', $this->data['assigned_to']);
                })->firstOrFail();
                if ($user->email_verified_at && $user->receive_notifications) {
                    $user->notify(new RequestRegisteredNotification("New Request", $this->data['description']));
                }
            });
        } catch (\Exception $e) {
            $users = User::role('support')->get();
            foreach ($users as $user) {
                $user->notify(new RequestRegisteredNotification("Error on user send request", $e->getMessage()));
            }
        }
    }
}
