<?php

namespace App\Repositories\Admin;

use App\Models\User\User;
use Yajra\DataTables\Facades\DataTables;

class MonthlyReportRepository extends BaseRepository
{
    public function getUserTable($request)
    {
        $data = User::query()
            ->select([
                'id',
                'cod_staff',
                'name'
            ])
            ->get();
        if ($request->ajax()) {
            return Datatables::of($data)
                ->make(true);
        }
        return false;
    }
}
