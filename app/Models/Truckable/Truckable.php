<?php

namespace App\Models\Truckable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Truckable extends Model
{
    use HasFactory,
        TruckableRelationships,
        TruckableModifiers;

    protected $table = 'truckables';
}
