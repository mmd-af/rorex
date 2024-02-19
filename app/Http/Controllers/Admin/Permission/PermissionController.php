<?php

namespace App\Http\Controllers\Admin\Permission;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Permission\StoreRequest;
use App\Repositories\Admin\PermissionRepository;

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

}
