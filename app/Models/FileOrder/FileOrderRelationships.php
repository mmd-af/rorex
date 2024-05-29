<?php

namespace App\Models\FileOrder;

use App\Models\TransportOrder\TransportOrder;

trait FileOrderRelationships
{
    public function transportOrder()
    {
        return $this->belongsTo(TransportOrder::class, 'order_id');
    }
}
