<?php

namespace App\Models\LetterAssignment;

use App\Models\StaffRequest\StaffRequest;
use App\Models\User\User;
use Spatie\Permission\Models\Role;

trait LetterAssignmentRelationships
{
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function request()
    {
        return $this->belongsTo(StaffRequest::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function signedBy()
    {
        return $this->belongsTo(User::class, 'signed_by');
    }
}
