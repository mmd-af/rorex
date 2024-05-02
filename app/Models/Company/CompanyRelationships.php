<?php

namespace App\Models\Company;

use App\Models\User\User;

trait CompanyRelationships
{
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
