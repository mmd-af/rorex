<?php

namespace App\Repositories\Admin;

use App\Models\CmrOrder\CmrOrder;
use App\Models\TransportOrder\TransportOrder;
use Yajra\DataTables\Facades\DataTables;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

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
                'truck_id'
            ])
            ->where('contract', '<>', null)
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
                ->addColumn('action', function ($row) {
                    $url = route('admin.orders.show', $row->id);
                    return '<a href="' . $url . '" class="btn btn-info text-white btn-sm mx-3">
                        <i class="fa-solid fa-eye"></i>
                        </a>';
                })
                ->rawColumns(['company', 'transportation', 'truck', 'action'])
                ->make(true);
        }
        return false;
    }
    public function show($order)
    {
        return $this->query()
            ->select([
                'id',
                'company_id',
                'transportation_id',
                'truck_id',
                'price',
                'last_price',
                'contract'
            ])
            ->where('id', $order)
            ->with(['company', 'company.users', 'transportation', 'transportation.trucks', 'truck', 'cmrOrders'])
            ->first();
    }
    public function uploadCmr($request)
    {
        DB::beginTransaction();
        try {
            $cmrFile = $request->file('cmr');
            $filename = Str::uuid() . '.' . $cmrFile->getClientOriginalExtension();
            $cmrFile->move(public_path('cmrs'), $filename);
            $cmrOrder = new CmrOrder();
            $cmrOrder->order_id = $request->input('order_id');
            $cmrOrder->cmr =  '/cmrs/' . $filename;
            $cmrOrder->save();
            DB::commit();
            Session::flash('message', 'Proccess was Completed Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('error', $e->getMessage());
        }
    }
}
