<?php

namespace App\Models\StaffRequest;

use App\Models\User\User;

trait StaffRequestRelationships
{
    public function reader()
    {
        return $this->belongsTo(User::class, 'read_by');
    }
}
