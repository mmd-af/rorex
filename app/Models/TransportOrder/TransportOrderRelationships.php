<?php

namespace App\Models\TransportOrder;

use App\Models\Company\Company;
use App\Models\Transportation\Transportation;
use App\Models\Truck\Truck;
use App\Models\CmrOrder\CmrOrder;
use App\Models\InvoiceOrder\InvoiceOrder;

trait TransportOrderRelationships
{
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function transportation()
    {
        return $this->belongsTo(Transportation::class);
    }

    public function truck()
    {
        return $this->belongsTo(Truck::class);
    }
    public function cmrOrders()
    {
        return $this->hasMany(CmrOrder::class, 'order_id');
    }
    public function invoiceOrders()
    {
        return $this->hasMany(InvoiceOrder::class, 'order_id');
    }
}
