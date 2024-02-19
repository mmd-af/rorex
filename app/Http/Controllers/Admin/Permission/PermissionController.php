<?php

namespace App\Http\Controllers\Admin\Permission;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Permission\StoreRequest;
use App\Http\Requests\Admin\Permission\UpdateRequest;
use App\Repositories\Admin\PermissionRepository;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    protected $permissionRepository;

    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    public function index()
    {
        return view('admin.permissions.index');
    }

    public function store(StoreRequest $request)
    {
        $this->permissionRepository->store($request);
        return redirect()->route('admin.permissions.index');
    }

    public function update(UpdateRequest $request, Permission $permission)
    {
        $this->permissionRepository->update($request, $permission);
        return redirect()->route('admin.permissions.index');
    }

}
