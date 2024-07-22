<?php

namespace App\Repositories\Admin;

use App\Models\Employee\Employee;
use App\Models\User\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class EmployeeRepository extends BaseRepository
{
    public function __construct(Employee $model)
    {
        $this->setModel($model);
    }

    public function getDataTable($request)
    {
        $data = $this->query()
            ->select([
                'id',
                'user_id',
                'staff_code',
                'first_name',
                'last_name',
                'department',
                'cart_number'
            ])
            ->with('users')
            ->get();

        if ($request->ajax()) {
            return Datatables::of($data)
                ->addColumn('email', function ($row) {
                    return $row->users->email;
                })
                ->addColumn('is_active', function ($row) {
                    return '
                    <div class="form-switch">
                    <input onclick="handleActive(event, ' . $row->users->id . ')" class="form-check-input" type="checkbox" role="switch" id="signed_by" name="signed_by" value="' . $row->users->is_active . '" ' . ($row->users->is_active ? 'checked' : '') . '>
                    </div>';
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
        $employee = $this->query()
            ->select([
                'id',
                'user_id',
                'staff_code',
                'last_name',
                'first_name',
                'department',
                'cart_number'
            ])
            ->where('id', $request->id)
            ->with(['users'])
            ->first();

        return response()->json($employee);
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

    public function update($request, $employee)
    {
        DB::beginTransaction();
        try {
            $employee->staff_code = $request->staff_code;
            $employee->last_name = $request->last_name;
            $employee->first_name = $request->first_name;
            $employee->cart_number = $request->cart_number;
            $employee->department = $request->department;
            $employee->save();
            DB::commit();
            Session::flash('message', 'The Update Operation was Completed Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('error', $e->getMessage());
        }
    }
}
