<?php

namespace App\Models\Truck;

trait TruckRelationships
{
    public function getCoveredAttribute($is_active)
    {
        return $is_active ? 'Yes' : 'No';
    }
}
