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

}
