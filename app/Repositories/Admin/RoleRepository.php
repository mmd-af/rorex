<?php

namespace App\Repositories\Admin;

use Illuminate\Support\Facades\Session;
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
                          <button onclick="destroy(' . $row->id . ')" type="button"
                                    class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#forgetRequest">
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
        $role = new Role();
        $role->name = $request->name;
        $role->guard_name = $request->guard_name;
        $role->save();
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
