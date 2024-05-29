<?php

namespace App\Models\CmrOrder;

use App\Models\TransportOrder\TransportOrder;

trait CmrOrderRelationships
{
    public function transportOrder()
    {
        return $this->belongsTo(TransportOrder::class, 'order_id');
    }
}
