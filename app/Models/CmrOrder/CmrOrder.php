<?php

namespace App\Models\CmrOrder;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CmrOrder extends Model
{
    use HasFactory,
        CmrOrderRelationships,
        CmrOrderModifiers;

    protected $table = 'cmr_orders';
}
