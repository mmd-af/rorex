<?php

namespace App\Models\Company;

use App\Models\User\User;
use App\Models\Truck\Truck;
use App\Models\Transportation\Transportation;

trait CompanyRelationships
{
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function trucks()
    {
        return $this->morphToMany(Truck::class, 'truckable');
    }
    public function transportations()
    {
        return $this->belongsToMany(Transportation::class)->withPivot('is_active');
    }
}
