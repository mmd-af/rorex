<?php

namespace App\Repositories\Admin;

use App\Models\Company\Company;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class CompanyRepository extends BaseRepository
{
    public function __construct(Company $model)
    {
        $this->setModel($model);
    }

    public function getDataTable($request)
    {
        $data = $this->query()
            ->select([
                'id',
                'user_id',
                'company_name',
                'activity_domain',
                'city'
            ])
            ->with('users')
            ->get();

        if ($request->ajax()) {
            return Datatables::of($data)
                ->addColumn('email', function ($row) {
                    return $row->users->email;
                })
                ->addColumn('is_active', function ($row) {
                    return $row->users->is_active ? 'Active' : 'Deactive';
                })
                ->addColumn('action', function ($row) {
                    return '<button onclick="show(' . $row->id . ')" type="button"
                                    class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#show">
                               <i class="fa-solid fa-eye"></i>
                            </button>';
                })
                ->rawColumns(['email', 'is_active', 'action'])
                ->make(true);
        }
        return false;
    }
    public function show($request)
    {
        $company = $this->query()
            ->select([
                'id',
                'company_name',
                'activity_domain',
                'city',
                'email',
                'is_active'
            ])
            ->where('id', $request->id)
            ->with(['permissions', 'roles'])
            ->first();
        $roles = Role::all();
        $permissions = Permission::all();
        $responseData = [
            'company' => $company,
            'roles' => $roles,
            'permissions' => $permissions
        ];
        return response()->json($responseData);
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
}
