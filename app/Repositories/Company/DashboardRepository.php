<?php

namespace App\Repositories\Company;

use App\Models\TransportOrder\TransportOrder;
use App\Models\Truck\Truck;
use Illuminate\Support\Facades\Auth;
use App\Models\Company\Company;

class DashboardRepository extends BaseRepository
{
    public function __construct(Company $model)
    {
        $this->setModel($model);
    }
    public function getCompany()
    {
        $userId = Auth::id();
        return $this->query()
            ->select([
                'id',
                'user_id',
                'company_name',
                'activity_domain',
                'vat_id',
                'registration_number',
                'phone_number'
            ])
            ->where('user_id', $userId)
            ->with(['users', 'trucks'])
            ->first();
    }
    public function getTrucks()
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
    public function syncTruckForCompany($request)
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

    public function getTransportations()
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
    public function getOrders($company)
    {
        return TransportOrder::query()
            ->select([
                'id',
                'company_id',
                'transportation_id',
                'truck_id',
                'price'
            ])
            ->where('company_id', $company->id)
            ->with(['company', 'transportation', 'truck'])
            ->get();
    }
    static function checkCompanyOrder($request)
    {
        return TransportOrder::query()
            ->select([
                'id',
                'company_id',
                'transportation_id'
            ])
            ->where('company_id', $request->company_id)
            ->where('transportation_id', $request->transportationId)
            ->first();
    }
    public function storeTransportOrder($request)
    {
        $checkCompanyOrder = $this->checkCompanyOrder($request);
        if ($checkCompanyOrder === null) {
            $data = $request->all();
            foreach ($data as $key => $value) {
                if (is_numeric($key) && is_numeric($value)) {
                    if ($value > 0) {
                        $transportOrder = new TransportOrder();
                        $transportOrder->company_id = $request->company_id;
                        $transportOrder->transportation_id = $request->transportationId;
                        $transportOrder->truck_id = $key;
                        $transportOrder->price = $value;
                        $transportOrder->save();
                    }
                }
            }
        } else {
            dd("sharmande");
        }
    }
}
