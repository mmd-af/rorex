<?php

namespace App\Http\Controllers\Admin\Order;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\OrderRepository;
use App\Http\Requests\Admin\Order\StoreRequest;
use App\Models\TransportOrder\TransportOrder;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function index()
    {
        return view('admin.orders.index');
    }
    public function show($order)
    {
        $order = $this->orderRepository->show($order);
        return view('admin.orders.show', compact('order'));
    }
    public function uploadCmr(Request $request)
    {
        $this->orderRepository->uploadCmr($request);
        return redirect()->route('admin.orders.show', $request->order_id);
    }
    public function cmrDestroy($cmr)
    {
        $order = $this->orderRepository->cmrDestroy($cmr);
        return redirect()->route('admin.orders.show', $order);
    }
    public function uploadFile(Request $request)
    {
        $this->orderRepository->uploadFile($request);
        return redirect()->route('admin.orders.show', $request->order_id);
    }
    public function fileDestroy($cmr)
    {
        $order = $this->orderRepository->fileDestroy($cmr);
        return redirect()->route('admin.orders.show', $order);
    }
    public function closeOrder(Request $request)
    {
        $this->orderRepository->closeOrder($request);
        return redirect()->route('admin.orders.show', $request->order_id);
    }
}
