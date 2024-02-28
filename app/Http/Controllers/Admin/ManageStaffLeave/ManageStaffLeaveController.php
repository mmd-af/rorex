<?php

namespace App\Http\Controllers\Admin\ManageStaffLeave;

use App\Http\Controllers\Controller;
use App\Models\User\User;
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

    public function update(Request $request, User $user)
    {
        $this->manageStaffLeaveRepository->update($request, $user);
        return redirect()->route('admin.manageStaffLeaves.index');
    }
}
