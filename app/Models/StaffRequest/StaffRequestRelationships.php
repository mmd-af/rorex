<?php

namespace App\Models\StaffRequest;

use App\Models\LetterAssignment\LetterAssignment;
use App\Models\User\User;
use App\Models\Leave\Leave;

trait StaffRequestRelationships
{
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reader()
    {
        return $this->belongsTo(User::class, 'read_by');
    }

    public function assignments()
    {
        return $this->hasMany(LetterAssignment::class, 'request_id');
    }
    public function leave()
    {
        return $this->hasOne(Leave::class, 'request_id');
    }
}
