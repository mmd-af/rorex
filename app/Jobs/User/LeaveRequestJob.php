<?php

namespace App\Jobs\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Leave\Leave;
use App\Models\User\User;
use Spatie\Permission\Models\Role;
use App\Models\StaffRequest\StaffRequest;
use App\Models\LetterAssignment\LetterAssignment;
use App\Notifications\RequestRegisteredNotification;

class LeaveRequestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $data;
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function handle(): void
    {
        try {
            $staffRequest = new StaffRequest();
            $staffRequest->name = $this->data['name'];
            $staffRequest->user_id = $this->data['userId'];
            $staffRequest->email = $this->data['email'];
            $staffRequest->mobile_phone = $this->data['mobile_phone'];
            $staffRequest->subject = $this->data['subject'];
            $staffRequest->description = $this->data['description'];
            $staffRequest->organization = $this->data['organization'];
            $staffRequest->cod_staff = $this->data['cod_staff'];
            $staffRequest->vacation_day = $this->data['vacation_day'];
            $staffRequest->save();
            $staffRequestId = $staffRequest->id;

            $role = Role::query()
                ->select(['id', 'name'])
                ->where('name', $this->data['departamentRole'])
                ->first();

            $leave = new Leave();
            $leave->user_id = $this->data['userId'];
            $leave->request_id = $staffRequestId;
            $leave->start_date = $this->data['start_date'];
            $leave->end_date = $this->data['end_date'];
            $leave->type = $this->data['type'];
            $leave->file = $this->data['file'];
            $leave->hour = $this->data['hour'];
            $leave->description = $this->data['description'];
            $leave->remaining = $this->data['remaining'];
            $leave->save();

            $assignment = new LetterAssignment();
            $assignment->user_id = $this->data['userId'];
            $assignment->request_id = $staffRequestId;
            $assignment->role_id = $role->id;
            $assignment->assigned_to = $this->data['assigned_to'];
            $assignment->status = "waiting";
            $assignment->save();

            $user = User::find($this->data['assigned_to']);
            if ($user->email_verified_at !== null && $user->receive_notifications) {
                $user->notify(new RequestRegisteredNotification("New Request", $this->data['description']));
            }
        } catch (\Exception $e) {
            $users = User::role('support')->get();
            foreach ($users as $user) {
                $user->notify(new RequestRegisteredNotification("Error on user send leave request", $e->getMessage()));
            }
        }
    }
}
