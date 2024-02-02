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
                'email',
                'subject',
                'description',
                'organization',
                'cod_staff',
                'read_at',
                'created_at',
                'is_archive'
            ])
            ->get();

        if ($request->ajax()) {
            return Datatables::of($data)
                ->addColumn('created_at', function($row){
                    $originalDate = Carbon::parse($row->created_at);
                    return $originalDate->format('Y-m-d');
                })
                ->rawColumns(['description', 'action'])
                ->make(true);
        }
        return false;
    }
}
