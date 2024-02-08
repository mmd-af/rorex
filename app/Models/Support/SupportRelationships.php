<?php

namespace App\Models\Support;

use App\Models\User\User;

trait SupportRelationships
{
    public function reader()
    {
        return $this->belongsTo(User::class, 'read_by');
    }
}
