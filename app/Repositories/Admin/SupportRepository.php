<?php

namespace App\Repositories\Admin;

use App\Models\Support\Support;
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
                    return '<button onclick="showMessageModal(' . $row->id . ')" type="button"
                                    class="btn btn-info btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#forgetRequest">
                                <i class="fa-solid fa-square-arrow-up-right"></i>
                            </button>';
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

    public function storeReaded($request)
    {
        return $this->query()
            ->select([
                'id',
                'name',
                'cod_staff',
                'email',
                'mobile_phone',
                'subject',
                'description',
                'organization',
                'cod_staff',
                'read_by',
                'read_at',
                'created_at',
                'is_archive'
            ])
            ->where('id', $request->id)
            ->first();

    }
}
