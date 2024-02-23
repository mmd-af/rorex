<?php

namespace App\Http\Controllers\Admin\ManageRequest;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ManageRequest\ManageRequestStoreRequest;
use App\Repositories\Admin\ManageRequestRepository;

class ManageRequestController extends Controller
{
    protected $manageRequestRepository;

    public function __construct(ManageRequestRepository $manageRequestRepository)
    {
        $this->manageRequestRepository = $manageRequestRepository;
    }

    public function index()
    {
        return view('admin.manageRequests.index');
    }

    public function store(ManageRequestStoreRequest $request)
    {
        $this->manageRequestRepository->store($request);
        return redirect()->route('admin.manageRequests.index');
    }
}
