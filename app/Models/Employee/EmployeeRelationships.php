<?php

namespace App\Models\Employee;

use App\Models\User\User;

trait EmployeeRelationships
{
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
