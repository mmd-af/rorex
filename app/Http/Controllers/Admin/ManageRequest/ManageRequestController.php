<?php

namespace App\Http\Controllers\Admin\ManageRequest;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ManageRequest\ManageRequestStoreRequest;
use App\Repositories\Admin\ManageRequestRepository;
use Illuminate\Http\Request;

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
    public function fullLetters()
    {
        return view('admin.manageRequests.fullLetters');
    }

    public function store(ManageRequestStoreRequest $request)
    {
        $this->manageRequestRepository->store($request);
        return redirect()->route('admin.manageRequests.index');
    }

    public function archived()
    {
        return view('admin.manageRequests.archived');
    }

    public function exportPDF(Request $request)
    {
        return $this->manageRequestRepository->exportPDF($request);
    }
}
