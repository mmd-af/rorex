<?php

namespace App\Repositories\Admin;

use App\Models\Support\Support;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class SupportRepository extends BaseRepository
{
    public function __construct(Support $model)
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
                'subject',
                'description',
                'organization',
                'cod_staff',
                'read_by',
                'read_at',
                'created_at',
                'is_archive'
            ])
            ->get();

        if ($request->ajax()) {
            return Datatables::of($data)
//                ->addColumn('created_at', function($row){
//                    $originalDate = Carbon::parse($row->created_at);
//                    return $originalDate->format('Y-m-d');
//                })
                ->addColumn('button', function ($row) {
                    return '<button class="btn btn-info btn-sm" onclick="showMessageModal(' . $row->id . ')"><i class="fa-solid fa-newspaper"></i></button>';
                })
                ->rawColumns(['button'])
//                ->rawColumns(['button'])
//                ->addColumn('p_name', function ($plane) {
//                    return implode(', ', $plane->presidents->pluck('P_name')->toArray());
//                })
//                ->rawColumns(['p_name'])
                ->make(true);
        }
        return false;
    }
}
