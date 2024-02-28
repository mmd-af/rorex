<?php

namespace App\Http\Controllers\Admin\ManageStaffLeave;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\ManageStaffLeaveRepository;
use Illuminate\Http\Request;

class ManageStaffLeaveAjaxController extends Controller
{
    protected $manageStaffLeaveRepository;

    public function __construct(ManageStaffLeaveRepository $manageStaffLeaveRepository)
    {
        $this->manageStaffLeaveRepository = $manageStaffLeaveRepository;
    }

    public function getDataTable(Request $request)
    {
        return $this->manageStaffLeaveRepository->getDataTable($request);
    }

}
