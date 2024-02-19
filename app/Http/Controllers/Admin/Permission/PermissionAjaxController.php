<?php

namespace App\Http\Controllers\Admin\Permission;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\PermissionRepository;
use Illuminate\Http\Request;

class PermissionAjaxController extends Controller
{
    protected $permissionRepository;

    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    public function getDataTable(Request $request)
    {
        return $this->permissionRepository->getDataTable($request);
    }

    public function destroy(Request $request)
    {
        return $this->permissionRepository->destroy($request);
    }
}
