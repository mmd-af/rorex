<?php

namespace App\Repositories\Admin;

use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class RoleRepository extends BaseRepository
{
    public function __construct(Role $model)
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
                                    data-bs-target="#showRoles">
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

    public function getPermissions()
    {
        return Permission::query()
            ->select([
                'id',
                'name'
            ])
            ->get();
    }

    public function store($request)
    {
        $role = new Role();
        $role->name = $request->name;
        $role->guard_name = $request->guard_name;
        $role->save();
        $permission = $request->except('_token', 'name', 'guard_name');
        $role->givePermissionTo($permission);
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
            ->with('permissions')
            ->first();

        $permissions = $this->getPermissions();
        $responseData = [
            'role' => $role,
            'permissions' => $permissions,
        ];

        return response()->json($responseData);


    }

    public function update($request, $role)
    {
        $role->name = $request->name;
        $role->save();
        $permissions = $request->except('_token', 'guard_name', 'name', '_method');
        $role->syncPermissions($permissions);
        Session::flash('message', 'The Operation was Completed Successfully');
    }

    public function destroy($request)
    {
        $role = $this->query()
            ->select('id')
            ->where('id', $request->id)
            ->first();
        $role->delete();
        Session::flash('message', 'The Operation was Completed Successfully');
    }
}
