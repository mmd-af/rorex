<?php

namespace App\Repositories\Admin;

use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class PermissionRepository extends BaseRepository
{
    public function __construct(Permission $model)
    {
        $this->setModel($model);
    }

    public function getDataTable($request)
    {
        $data = $this->query()
            ->select([
                'id',
                'name',
            ])
            ->get();
        if ($request->ajax()) {
            return Datatables::of($data)
                ->addColumn('button', function ($row) {
                    return '
                         <button onclick="show(' . $row->id . ')" type="button"
                                    class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#showPermissions">
                               <i class="fa-solid fa-eye"></i>
                            </button>
                            <button onclick="destroy(' . $row->id . ')"
                                    class="btn btn-danger btn-sm">
                               <i class="fa-solid fa-trash"></i>
                            </button>';
                })
                ->rawColumns(['button'])
                ->make(true);
        }
        return false;
    }

    public function store($request)
    {
        $permission = new Permission();
        $permission->name = $request->name;
        $permission->guard_name = $request->guard_name;
        $permission->save();
        Session::flash('message', 'The Operation was Completed Successfully');
    }

    public function show($request)
    {
        $role = $this->query()
            ->select([
                'id',
                'name'
            ])
            ->where('id', $request->id)
            ->first();
        return response()->json($role);
    }

    public function update($request, $permission)
    {
        $permission->name = $request->name;
        $permission->save();
        Session::flash('message', 'The Operation was Completed Successfully');
    }

    public function destroy($request)
    {
        $permission = $this->query()
            ->select('id')
            ->where('id', $request->id)
            ->first();
        $permission->delete();
        Session::flash('message', 'The Operation was Completed Successfully');
    }
}
