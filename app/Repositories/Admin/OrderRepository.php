<?php

namespace App\Repositories\Admin;

use App\Models\CmrOrder\CmrOrder;
use App\Models\FileOrder\FileOrder;
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
            ->where('is_active', 1)
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

    public function getArchiveDataTable($request)
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
            ->where('is_active', 0)
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
                    $url = route('admin.orders.show_archive', $row->id);
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
            ->with(['company', 'company.users', 'transportation', 'transportation.trucks', 'truck', 'cmrOrders', 'invoiceOrders', 'fileOrders'])
            ->first();
    }
    public function show_archive($order)
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
            ->with(['company', 'company.users', 'transportation', 'transportation.trucks', 'truck', 'cmrOrders', 'invoiceOrders', 'fileOrders'])
            ->first();
    }    public function uploadCmr($request)
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

    public function cmrDestroy($cmr)
    {
        $cmrOrder = CmrOrder::findOrFail($cmr);
        $orderId = $cmrOrder->order_id;
        $filePath = public_path($cmrOrder->cmr);
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        $cmrOrder->delete();
        return $orderId;
    }
    public function uploadFile($request)
    {
        DB::beginTransaction();
        try {
            $fileFile = $request->file('file');
            $filename = Str::uuid() . '.' . $fileFile->getClientOriginalExtension();
            $fileFile->move(public_path('files'), $filename);
            $fileOrder = new FileOrder();
            $fileOrder->name = $request->input('name');
            $fileOrder->order_id = $request->input('order_id');
            $fileOrder->file =  '/files/' . $filename;
            $fileOrder->save();
            DB::commit();
            Session::flash('message', 'Proccess was Completed Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('error', $e->getMessage());
        }
    }
    public function fileDestroy($file)
    {
        $fileOrder = FileOrder::findOrFail($file);
        $orderId = $fileOrder->order_id;
        $filePath = public_path($fileOrder->file);
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        $fileOrder->delete();
        return $orderId;
    }
    public function closeOrder($request)
    {
        $order = $this->findOrFail($request->order_id);
        $order->is_active = 0;
        $order->save();
    }
}
