<?php

namespace App\Repositories\Admin;

use App\Models\Transportation\Transportation;
use App\Models\Truck\Truck;
use App\Models\Truckable\Truckable;
use App\Models\Company\Company;
use App\Models\TransportOrder\TransportOrder;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

class TransportationRepository extends BaseRepository
{
    public function __construct(Transportation $model)
    {
        $this->setModel($model);
    }

    public function getDataTable($request)
    {
        $data = $this->query()
            ->select([
                'id',
                'user_id',
                'product_name',
                'from_date',
                'until_date',
                'country_of_origin',
                'city_of_origin',
                'destination_country',
                'destination_city',
                'truck_type',
                'weight_of_each_car',
                'description',
                'is_active'
            ])
            ->with('users')
            ->get();

        if ($request->ajax()) {
            return Datatables::of($data)
                ->addColumn('is_active', function ($row) {
                    return '
                    <div class="form-switch">
                    <input onclick="handleActive(event, ' . $row->id . ')" class="form-check-input" type="checkbox" role="switch" id="signed_by" name="signed_by" value="' . $row->is_active . '" ' . ($row->is_active ? 'checked' : '') . '>
                    </div>';
                })
                ->addColumn('action', function ($row) {
                    return '<button onclick="showOrder(' . $row->id . ')" type="button"
                       class="btn btn-info text-white btn-sm mx-3" data-bs-toggle="modal"
                        data-bs-target="#showOrder">
                        <i class="fa fa-first-order" aria-hidden="true"></i>
                        </button>
                        <button onclick="show(' . $row->id . ')" type="button"
                           class="btn btn-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#show">
                         <i class="fa-solid fa-eye"></i>
                        </button>';
                })
                ->rawColumns(['is_active', 'action'])
                ->make(true);
        }
        return false;
    }
    public function show($request)
    {
        $company = $this->query()
            ->select([
                'id',
                'user_id',
                'product_name',
                'from_date',
                'until_date',
                'country_of_origin',
                'city_of_origin',
                'destination_country',
                'destination_city',
                'truck_type',
                'weight_of_each_car',
                'description',
                'is_active'
            ])
            ->where('id', $request->id)
            ->with(['users', 'trucks', 'companies'])
            ->first();

        return response()->json($company);
    }
    public function showCompaniesOrder($request)
    {
        $reasporOrder = TransportOrder::query()
            ->select([
                'id',
                'company_id',
                'transportation_id',
                'truck_id',
                'price'
            ])
            ->where('transportation_id', $request->id)
            ->with(['company.users', 'transportation', 'truck'])
            ->get();
        return response()->json($reasporOrder);
    }
    public function active($request)
    {
        DB::beginTransaction();
        try {
            $transportation = $this->findOrFail($request->id);
            $transportation->is_active = !$transportation->is_active;
            $transportation->save();
            DB::commit();
            Session::flash('message', 'The Update Operation was Completed Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('error', $e->getMessage());
        }
        return false;
    }

    public function update($request, $company)
    {
        DB::beginTransaction();
        try {
            $company->is_active = $request->has('is_active') ? 1 : 0;
            $company->save();
            $company->syncRoles($request->roles);
            $company->syncPermissions($request->permissions);
            DB::commit();
            Session::flash('message', 'The Update Operation was Completed Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('error', $e->getMessage());
        }
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $transportation = new Transportation();
            $transportation->user_id = Auth::id();
            $transportation->product_name = $request->product_name;
            $transportation->from_date = $request->from_date;
            $transportation->until_date = $request->until_date;
            $transportation->country_of_origin = $request->country_of_origin;
            $transportation->city_of_origin = $request->city_of_origin;
            $transportation->destination_country = $request->destination_country;
            $transportation->destination_city = $request->destination_city;
            $transportation->truck_type = $request->truck_type;
            $transportation->weight_of_each_car = $request->weight_of_each_car;
            $transportation->description = $request->description;
            $transportation->save();
            $transportation->companies()->attach($request->authorized_company);
            $is_active_companies = $request->is_active;
            if (!empty($is_active_companies)) {
                $transportation->companies()->wherePivotIn('company_id', $is_active_companies)->update(['is_active' => true]);
            }
            foreach ($request->all() as $key => $value) {
                if (is_int($key)) {
                    $truck = Truck::find($key);
                    $transportation->trucks()->attach($truck, ['qty' => $value]);
                }
            }
            DB::commit();
            Session::flash('message', 'The Update Operation was Completed Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('error', $e->getMessage());
        }
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
    static function getCompanyWithTruckId($truckId)
    {
        return Truckable::where('truckable_type', Company::class)
            ->where('truck_id', $truckId)
            ->pluck('truckable_id')
            ->toArray();
    }

    public function getCompaniesWithTruck($request)
    {
        $uniqueIds = array_unique($request->id);
        $findCompanies = [];
        foreach ($uniqueIds as $truckId) {
            $companies = $this->getCompanyWithTruckId($truckId);
            array_push($findCompanies, ...$companies);
        }
        $uniqueCompanies = array_unique($findCompanies);
        $companies = [];
        foreach ($uniqueCompanies as $companyId) {
            $company = Company::query()
                ->select([
                    'id',
                    'user_id',
                    'company_name',
                    'vat_id'
                ])
                ->where('id', $companyId)
                ->with('users')
                ->first();
            $companies[] = $company;
        }
        return $companies;
    }
}
