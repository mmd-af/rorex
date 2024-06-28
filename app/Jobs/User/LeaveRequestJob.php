<?php

namespace App\Jobs\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User\User;
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
