<?php

namespace App\Repositories\Admin;

use App\Models\Employee\Employee;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class ManageStaffLeaveRepository extends BaseRepository
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
                'staff_code',
                'last_name',
                'first_name',
                'leave_balance'
            ])
            ->get();
        if ($request->ajax()) {
            return Datatables::of($data)
                ->addColumn('leave_balance', function ($row) {
                    $url = route('admin.manageStaffLeaves.update', $row->id);
                    $csrf = csrf_field();
                    $method = method_field('PUT');
                    return '<form class="d-flex justify-content-between" action="' . $url . '" method="POST">
                ' . $csrf . '
                ' . $method . '
                <input type="text" class="form-control form-control-sm mx-2" name="leave_balance" id="leave_balance" value="' . $row->leave_balance . '"> (Hour) 
                <button type="submit" class="btn btn-success mx-2"><i class="fa-solid fa-square-check"></i></button>
               </form>';
                })
                ->rawColumns(['leave_balance'])
                ->make(true);
        }
        return false;
    }

    public function update($request, $employee)
    {
        DB::beginTransaction();
        try {
            $employee->leave_balance = $request->leave_balance;
            $employee->save();
            DB::commit();
            Session::flash('message', 'The Update Operation was Completed Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('error', $e->getMessage());
        }
    }

}
