<?php

namespace App\Repositories\Company;

use App\Models\Truck\Truck;
use Illuminate\Support\Facades\Auth;
use App\Models\Company\Company;

class DashboardRepository extends BaseRepository
{
    public function __construct(Company $model)
    {
        $this->setModel($model);
    }


    function getCompany()
    {
        $userId = Auth::id();
        return $this->query()
            ->select([
                'id',
                'user_id'
            ])
            ->where('user_id', $userId)
            ->with('trucks')
            ->first();
    }
    function getTrucks()
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
