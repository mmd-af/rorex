<?php

namespace App\Models\Leave;

use App\Models\StaffRequest\StaffRequest;
use App\Models\User\User;

trait LeaveRelationships
{
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function requests()
    {
        return $this->belongsTo(StaffRequest::class, 'request_id');
    }
}
