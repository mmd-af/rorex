<?php

namespace App\Repositories\Admin;

use App\Models\TransportOrder\TransportOrder;
use Yajra\DataTables\Facades\DataTables;

class OrderRepository extends BaseRepository
{
    public function __construct(TransportOrder $model)
    {
        $this->setModel($model);
    }

    public function getDataTable($request)
    {
        $data = $this->query()
            ->select([
                'id',
                'company_id',
                'transportation_id',
                'truck_id',
                'price',
                'last_price',
                'contract'
            ])
            ->with(['company', 'transportation', 'truck'])
            ->get();

        if ($request->ajax()) {
            return Datatables::of($data)
                ->addColumn('company', function ($row) {
                    return $row->company->company_name;
                })
                ->addColumn('transportation', function ($row) {
                    return $row->transportation->product_name . " / " . $row->transportation->product_number;
                })
                ->addColumn('truck', function ($row) {
                    return $row->truck->name;
                })
                ->addColumn('contract', function ($row) {
                    if ($row->contract) {
                        return '<a href="' . asset($row->contract) . '" download>Download</a>';
                    }
                    return 'No Contract';
                })
                ->addColumn('action', function ($row) {
                    return '<button onclick="showOrder(' . $row->id . ')" type="button"
                       class="btn btn-success text-white btn-sm mx-3" data-bs-toggle="modal"
                        data-bs-target="#showOrder">
                        <i class="fa-solid fa-accept"></i>
                        </button>
                        <button onclick="show(' . $row->id . ')" type="button"
                           class="btn btn-danger btn-sm" data-bs-toggle="modal"
                            data-bs-target="#show">
                         <i class="fa-solid fa-trash"></i>
                        </button>';
                })
                ->rawColumns(['company', 'transportation', 'truck', 'contract', 'action'])
                ->make(true);
        }
        return false;
    }
}
