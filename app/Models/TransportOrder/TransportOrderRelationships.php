<?php

namespace App\Models\TransportOrder;

use App\Models\Company\Company;
use App\Models\Transportation\Transportation;
use App\Models\Truck\Truck;

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
}
