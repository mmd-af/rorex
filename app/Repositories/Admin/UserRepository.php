<?php

namespace App\Repositories\Admin;

use App\Imports\DailyReportExcel;
use App\Models\User\User;
use Exception;
use Illuminate\Support\Facades\DB;
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
                'prenumele_tatalui',
                'name',
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
                ->rawColumns(['show'])
                ->make(true);
        }
        return false;
    }

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
                $dataAbsolvirii = date('Y-m-d', strtotime($item[21]));
                $dataPlecarii = date('Y-m-d', strtotime($item[24]));
                $codStaff = (int)$item[0];
                $cod_staff = (int)$item[1];
                $data = [
                    'id' => $codStaff,
                    'cod_staff' => $cod_staff,
                    'name' => $item[2],
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
//                    'email' => $item[12]
                ];

                $condition = [
                    'cod_staff' => $codStaff
                ];
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
                'email'
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
            $user->syncRoles($request->roles);
            $user->syncPermissions($request->permissions);
            DB::commit();
            Session::flash('message', 'The Update Operation was Completed Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('error', $e->getMessage());
        }
    }

}
