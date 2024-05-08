<?php

namespace App\Repositories\Admin;

use App\Models\Truck\Truck;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

class TruckRepository extends BaseRepository
{
    public function __construct(Truck $model)
    {
        $this->setModel($model);
    }

    public function getDataTable($request)
    {
        $data = $this->query()
            ->select([
                'id',
                'name',
                'lwh',
                'total_height',
                'load_capacity',
                'covered',
                'is_active'
            ])
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
                'name',
                'lwh',
                'total_height',
                'load_capacity',
                'covered',
                'is_active'
            ])
            ->where('id', $request->id)
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
        $truck = new Truck();
        $truck->name = $request->name;
        $truck->lwh = $request->l . " L * " . $request->w . " W * " . $request->h . " H";
        $truck->total_height = $request->total_height;
        $truck->load_capacity = $request->load_capacity;
        $truck->covered = $request->covered;
        $truck->save();
    }
}
