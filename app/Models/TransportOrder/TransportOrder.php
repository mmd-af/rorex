<?php

namespace App\Models\TransportOrder;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransportOrder extends Model
{
    use HasFactory,
        TransportOrderRelationships,
        TransportOrderModifiers;

    protected $table = 'transportation_orders';
}
