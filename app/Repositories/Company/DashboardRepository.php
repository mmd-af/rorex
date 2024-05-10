<?php

namespace App\Repositories\Company;

use App\Models\Truck\Truck;

class DashboardRepository extends BaseRepository
{
    // public function __construct(Company $model)
    // {
    //     $this->setModel($model);
    // }
    function getTruck()
    {
        return Truck::query()
            ->select([
                'id',
                'name',
                'lwh',
                'load_capacity'
            ])
            ->where('is_active', 1)
            ->get();
    }
}
