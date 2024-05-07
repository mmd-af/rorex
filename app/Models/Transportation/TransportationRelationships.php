<?php

namespace App\Models\Transportation;

use App\Models\User\User;

trait TransportationRelationships
{
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
