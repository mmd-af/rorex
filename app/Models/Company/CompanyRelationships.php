<?php

namespace App\Models\Company;

use App\Models\User\User;
use App\Models\Truck\Truck;

trait CompanyRelationships
{
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function trucks()
    {
        return $this->morphToMany(Truck::class, 'truckable')->withPivot('qty');
    }
}
