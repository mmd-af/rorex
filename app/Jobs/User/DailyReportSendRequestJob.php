<?php

namespace App\Jobs\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Models\StaffRequest\StaffRequest;
use Spatie\Permission\Models\Role;
use App\Models\LetterAssignment\LetterAssignment;

class DailyReportSendRequestJob implements ShouldQueue
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

    /**
     * Execute the job.
     */
    public function handle(): void
    {
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
        $role = Role::query()
            ->select(['id', 'name'])
            ->where('name', $this->data['departamentRole'])
            ->first();
        $assignment = new LetterAssignment();
        $assignment->user_id = $this->data['userId'];
        $assignment->request_id = $staffRequest->id;
        $assignment->role_id = $role->id;
        $assignment->assigned_to = $this->data['assigned_to'];
        $assignment->status = "waiting";
        $assignment->save();
        Log::info("Result" . $staffRequest->name . " " . $assignment);
    }
}
