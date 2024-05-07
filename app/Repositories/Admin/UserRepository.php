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
                'cod_staff',
                'name',
                'first_name',
                'departament',
                'numar_card',
                'email',
                'is_active'
            ])

            ->get();
        if ($request->ajax()) {
            return Datatables::of($data)
                ->addColumn('show', function ($row) {
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
                ->rawColumns(['show', 'status', 'is_active'])
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
            $password = Hash::make('12345678');
            foreach ($files[0] as $key => $item) {
                if ($key === 0) {
                    continue;
                }
                $dataAderarii = date('Y-m-d', strtotime($item[7]));
                $dataNasterii = date('Y-m-d', strtotime($item[10]));
                $dataAbsolvirii = date('Y-m-d', strtotime($item[21]));
                $dataPlecarii = date('Y-m-d', strtotime($item[24]));
                $codStaff = (int)$item[0];
                $cod_staff = (int)$item[1];
                $data = [
                    'id' => $codStaff,
                    'cod_staff' => $cod_staff,
                    'name' => $item[2],
                    'first_name' => $item[22],
                    'departament' => $item[3],
                    'pozitie' => $item[4],
                    'numar_card' => $item[5],
                    'parola' => $item[6],
                    'data_aderarii' => $dataAderarii,
                    'sex' => $item[8],
                    'starea_civila' => $item[9],
                    'data_nasterii' => $dataNasterii,
                    'telefon' => $item[11],
                    'card_de_identitate' => $item[13],
                    'functie' => $item[14],
                    'tip_personal' => $item[15],
                    'cod_postal' => $item[16],
                    'status_politic' => $item[17],
                    'rezidenta' => $item[18],
                    'nationalitate' => $item[19],
                    'educatie' => $item[20],
                    'data_absolvirii' => $dataAbsolvirii,
                    'scoala' => $item[22],
                    'profesie' => $item[23],
                    'data_plecarii' => $dataPlecarii,
                    'prenumele_tatalui' => $item[25],
                    'adresa' => $item[26]
                ];
                $condition = [
                    'cod_staff' => $codStaff
                ];
                $existingUser = DB::table('users')->where($condition)->first();
                if (!$existingUser) {
                    $data['email'] = $item[12];
                    $data['password'] = $password;
                }
                DB::table('users')->updateOrInsert($condition, $data);
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
