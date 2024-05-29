<?php

namespace App\Models\InvoiceOrder;

use App\Models\TransportOrder\TransportOrder;

trait InvoiceOrderRelationships
{
    public function transportOrder()
    {
        return $this->belongsTo(TransportOrder::class, 'order_id');
    }
}
