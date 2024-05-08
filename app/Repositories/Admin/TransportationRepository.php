<?php

namespace App\Repositories\Admin;

use App\Models\Transportation\Transportation;
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
                    return '<button onclick="show(' . $row->id . ')" type="button"
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
                'estination_country',
                'estination_city',
                'truck_type',
                'weight_of_each_car',
                'description',
                'is_active'
            ])
            ->where('id', $request->id)
            ->with(['users'])
            ->first();

        return response()->json($company);
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

    function store($request)
    {
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
    }
}
