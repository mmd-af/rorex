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

class ManageStaffLeaveRepository extends BaseRepository
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
                <input type="hidden" name="leave_balance" value="' . $row->leave_balance . '">
                <input type="text" class="form-control form-control-sm" name="leave_balance_new" id="leave_balance_new" value="' . $row->leave_balance . '">
                <button type="submit" class="btn btn-success"><i class="fa-solid fa-square-check"></i></button>
            </form>';
                })
                ->rawColumns(['leave_balance'])
                ->make(true);
        }
        return false;
    }

    public function update($request, $user)
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

}
