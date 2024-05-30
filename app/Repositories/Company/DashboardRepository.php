<?php

namespace App\Repositories\Company;

use App\Models\TransportOrder\TransportOrder;
use App\Models\Truck\Truck;
use Illuminate\Support\Facades\Auth;
use App\Models\Company\Company;
use App\Models\InvoiceOrder\InvoiceOrder;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

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
            }, 'transportations.trucks', 'transportations.orders'])
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
                'price',
                'last_price',
                'contract'
            ])
            ->where('company_id', $company->id)
            ->with(['company', 'company.users', 'transportation', 'transportation.trucks', 'truck', 'cmrOrders', 'invoiceOrders', 'fileOrders'])
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
        }
    }
    public function uploadInvoice($request)
    {
        DB::beginTransaction();
        try {
            $invoiceFile = $request->file('invoice');
            $filename = Str::uuid() . '.' . $invoiceFile->getClientOriginalExtension();
            $invoiceFile->move(public_path('invoices'), $filename);
            $invoiceOrder = new InvoiceOrder();
            $invoiceOrder->order_id = $request->input('order_id');
            $invoiceOrder->invoice =  '/invoices/' . $filename;
            $invoiceOrder->save();
            DB::commit();
            Session::flash('message', 'Proccess was Completed Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('error', $e->getMessage());
        }
    }
    public function invoiceDestroy($invoice)
    {
        $invoiceOrder = InvoiceOrder::findOrFail($invoice);
        $filePath = public_path($invoiceOrder->invoice);
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        $invoiceOrder->delete();
    }
}
