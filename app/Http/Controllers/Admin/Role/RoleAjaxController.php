<?php

namespace App\Http\Controllers\Admin\Role;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\RoleRepository;
use Illuminate\Http\Request;

class RoleAjaxController extends Controller
{
    protected $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function getDataTable(Request $request)
    {
        return $this->roleRepository->getDataTable($request);
    }

    public function getPermissions()
    {
        return $this->roleRepository->getPermissions();
    }

    public function destroy(Request $request)
    {
        return $this->roleRepository->destroy($request);
    }
}
