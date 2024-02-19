<?php

namespace App\Http\Controllers\Admin\Role;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Role\StoreRequest;
use App\Repositories\Admin\RoleRepository;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    protected $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function index()
    {
        return view('admin.roles.index');
    }

    public function store(StoreRequest $request)
    {
        $this->roleRepository->store($request);
        return redirect()->route('admin.roles.index');
    }

    public function update(Request $request, Role $role)
    {
        $this->roleRepository->update($request, $role);
        return redirect()->route('admin.roles.index');
    }
}
