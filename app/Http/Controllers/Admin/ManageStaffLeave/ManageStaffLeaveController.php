<?php

namespace App\Http\Controllers\Admin\ManageStaffLeave;

use App\Http\Controllers\Controller;
use App\Models\Employee\Employee;
use App\Repositories\Admin\ManageStaffLeaveRepository;
use Illuminate\Http\Request;

class ManageStaffLeaveController extends Controller
{
    protected $manageStaffLeaveRepository;

    public function __construct(ManageStaffLeaveRepository $manageStaffLeaveRepository)
    {
        $this->manageStaffLeaveRepository = $manageStaffLeaveRepository;
    }

    public function index()
    {
        return view('admin.manageStaffLeaves.index');
    }

    public function update(Request $request, Employee $employee)
    {
        $this->manageStaffLeaveRepository->update($request, $employee);
        return redirect()->route('admin.manageStaffLeaves.index');
    }
}
