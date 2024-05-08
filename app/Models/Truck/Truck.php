<?php

namespace App\Models\Truck;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Truck extends Model
{
    use HasFactory,
        SoftDeletes,
        TruckRelationships,
        TruckModifiers;

    protected $table = 'trucks';
}
