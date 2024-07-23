<?php

namespace App\Repositories\Admin;

use App\Imports\DailyReportExcel;
use App\Models\User\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class UserRepository extends BaseRepository
{
    public function __construct(User $model)
    {
        $this->setModel($model);
    }

    public function getDataTable($request)
    {
        $data = $this->query()
            ->select([
                'id',
                'name',
                'email',
                'is_active'
            ])
            ->with(['employee', 'company'])
            ->get();
        if ($request->ajax()) {
            return Datatables::of($data)
                ->addColumn('type', function ($row) {
                    if ($row->employee) {
                        return "Employee";
                    } elseif ($row->company) {
                        return "Company";
                    } else {
                        return "Other";
                    }
                })
                ->addColumn('action', function ($row) {
                    return '<button onclick="show(' . $row->id . ')" type="button"
                                    class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#show">
                               <i class="fa-solid fa-eye"></i>
                            </button>';
                })
                ->addColumn('status', function ($row) {
                    return $row->is_active ? 'Active' : 'Deactive';
                })
                ->addColumn('is_active', function ($row) {
                    return '
                    <div class="form-switch">
                    <input onclick="handleActive(event, ' . $row->id . ')" class="form-check-input" type="checkbox" role="switch" id="signed_by" name="signed_by" value="' . $row->is_active . '" ' . ($row->is_active ? 'checked' : '') . '>
                    </div>';
                })
                ->rawColumns(['action', 'status', 'is_active'])
                ->make(true);
        }
        return false;
    }

    //    public function getLeaveBalanceData($request)
    //    {
    //        $data = $this->query()
    //            ->select([
    //                'id',
    //                'cod_staff',
    //                'first_name',
    //                'name',
    //                'leave_balance'
    //            ])
    //            ->get();
    //        if ($request->ajax()) {
    //            return Datatables::of($data)
    //                ->addColumn('leave_balance', function ($row) {
    //                    $url = route('admin.users.updateLeaveBalance', $row->id);
    //                    $csrf = csrf_field();
    //                    $method = method_field('PUT');
    //                    return '<form class="d-flex justify-content-between" action="' . $url . '" method="POST">
    //                ' . $csrf . '
    //                ' . $method . '
    //                <input type="hidden" name="leave_balance" value="' . $row->leave_balance . '">
    //                <input type="text" class="form-control form-control-sm" name="leave_balance_new" id="leave_balance_new" value="' . $row->leave_balance . '">
    //                <button type="submit" class="btn btn-success"><i class="fa-solid fa-square-check"></i></button>
    //            </form>';
    //                })
    //                ->rawColumns(['leave_balance'])
    //                ->make(true);
    //        }
    //        return false;
    //    }

    public function import($request)
    {
        $files = $request->file('file');
        $files = Excel::toArray(new DailyReportExcel, $files);
        $expectedHeaders = [
            "Cod staff",
            "Nume",
            "Departament",
            "Email"
        ];
        $actualHeaders = $files[0][0];
        $missingHeaders = array_diff($expectedHeaders, $actualHeaders);
        if (!empty($missingHeaders)) {
            Session::flash('error', 'Invalid file format. Missing headers: ' . implode(', ', $missingHeaders));
            return false;
        }
        DB::beginTransaction();
        try {
            foreach ($files[0] as $key => $item) {
                if ($key === 0) {
                    continue;
                }
                $dataAderarii = date('Y-m-d', strtotime($item[7]));
                $dataNasterii = date('Y-m-d', strtotime($item[10]));
                $dataPlecarii = date('Y-m-d', strtotime($item[24]));
                $codStaff = (int)$item[0];
                $employeeData = [
                    'staff_code' => $codStaff,
                    'first_name' => (!empty($item[25]) ? $item[25] : $item[22]),
                    'last_name' => $item[2],
                    'department' => $item[3],
                    'position' => $item[4],
                    'cart_number' => $item[5],
                    'password' => $item[6],
                    'joining_date' => $dataAderarii,
                    'gender' => $item[8],
                    'marital_status' => $item[9],
                    'birth_date' => $dataNasterii,
                    'phone' => $item[11],
                    'identity_card' => $item[13],
                    'postal_code' => $item[16],
                    'nationality' => $item[19],
                    'education' => $item[20],
                    'profession' => $item[23],
                    'departure_date' => $dataPlecarii,
                    'father_first_name' => $item[25],
                    'address' => $item[26]
                ];

                $existingEmployee = DB::table('employees')->where('staff_code', $codStaff)->first();
                if ($existingEmployee) {
                    DB::table('employees')->where('id', $existingEmployee->id)->update($employeeData);
                } else {
                    $user = new User();
                    $user->name = !empty($item[2]) ? $item[2] : (!empty($item[25]) ? $item[25] : $item[22]);
                    $user->email = $item[12];
                    $user->receive_notifications = 1;
                    $user->password = Hash::make('12345678');
                    $user->save();
                    $employeeData['user_id'] = $user->id;
                    DB::table('employees')->insert($employeeData);
                    $permission = Permission::where('name', 'employees')->first();
                    if (!$permission) {
                        $permission = new Permission();
                        $permission->name = "employees";
                        $permission->guard_name = "web";
                        $permission->save();
                    }
                    $user->givePermissionTo($permission);
                }
            }
            DB::commit();
            Session::flash('message', 'The Update Operation was Completed Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('error', $e->getMessage());
        }
    }


    public function show($request)
    {
        $user = $this->query()
            ->select([
                'id',
                'name',
                'email',
                'is_active'
            ])
            ->where('id', $request->id)
            ->with(['permissions', 'roles'])
            ->first();
        $roles = Role::all();
        $permissions = Permission::all();
        $responseData = [
            'user' => $user,
            'roles' => $roles,
            'permissions' => $permissions
        ];
        return response()->json($responseData);
    }

    public function update($request, $user)
    {
        DB::beginTransaction();
        try {
            $user->is_active = $request->has('is_active') ? 1 : 0;
            $user->save();
            $user->syncRoles($request->roles);
            $user->syncPermissions($request->permissions);
            DB::commit();
            Session::flash('message', 'The Update Operation was Completed Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('error', $e->getMessage());
        }
    }

    public function updateLeaveBalance($request, $user)
    {
        DB::beginTransaction();
        try {
            $user->leave_balance = $request->leave_balance_new;
            $user->save();
            DB::commit();
            Session::flash('message', 'The Update Operation was Completed Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('error', $e->getMessage());
        }
    }
    public function active($request)
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($request->id);
            $user->is_active = !$user->is_active;
            $user->save();
            DB::commit();
            Session::flash('message', 'The Update Operation was Completed Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('error', $e->getMessage());
        }
        return false;
    }
}
