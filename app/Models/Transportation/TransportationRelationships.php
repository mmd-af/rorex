<?php

namespace App\Models\Transportation;

use App\Models\User\User;
use App\Models\Truck\Truck;
use App\Models\Company\Company;

trait TransportationRelationships
{
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function trucks()
    {
        return $this->morphToMany(Truck::class, 'truckable')->withPivot('qty');
    }
    public function companies()
    {
        return $this->belongsToMany(Company::class);
    }
}
