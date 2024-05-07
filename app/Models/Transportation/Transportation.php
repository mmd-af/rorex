<?php

namespace App\Models\Transportation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transportation extends Model
{
    use HasFactory,
        SoftDeletes,
        TransportationRelationships,
        TransportationModifiers;

    protected $table = 'transportations';
}
