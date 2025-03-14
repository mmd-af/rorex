<?php

namespace App\Http\Controllers\Admin\ManageRequest;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\ManageRequestRepository;
use Illuminate\Http\Request;

class ManageRequestAjaxController extends Controller
{
    protected $manageRequestRepository;

    public function __construct(ManageRequestRepository $manageRequestRepository)
    {
        $this->manageRequestRepository = $manageRequestRepository;
    }

    public function getDataTable(Request $request)
    {
        return $this->manageRequestRepository->getDataTable($request);
    }
    public function getFullDataTable(Request $request)
    {
        return $this->manageRequestRepository->getFullDataTable($request);
    }

    public function sign(Request $request)
    {
        return $this->manageRequestRepository->sign($request);
    }

    public function setPass(Request $request)
    {
        return $this->manageRequestRepository->setPass($request);
    }

    public function setReject(Request $request)
    {
        return $this->manageRequestRepository->setReject($request);
    }

    public function getArchiveDataTable(Request $request)
    {
        return $this->manageRequestRepository->getArchiveDataTable($request);
    }
}
