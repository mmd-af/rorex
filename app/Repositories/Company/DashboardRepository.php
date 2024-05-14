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
    function syncTruckForCompany($request)
    {
        $truckId = $request->truckId;
        $company = $this->getCompany();

        if ($company->trucks->contains($truckId)) {
            $company->trucks()->detach($truckId);
            $message = 'Truck removed successfully from company';
        } else {
            $company->trucks()->attach($truckId);
            $message = 'Truck added successfully to company';
        }

        return response()->json(['message' => $message]);
    }

    function getTransportations()
    {
        $userId = Auth::id();
        $company = $this->query()
            ->select([
                'id',
                'user_id'
            ])
            ->where('user_id', $userId)
            ->with(['transportations' => function ($query) {
                $query->wherePivot('is_active', 1);
            }, 'transportations.trucks'])
            ->first();
        return $company->transportations->where('is_active', 1);
    }
}
