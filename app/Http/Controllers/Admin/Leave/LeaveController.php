<?php

namespace App\Http\Controllers\Admin\Leave;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\LeaveRepository;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    protected $leaveRepository;

    public function __construct(LeaveRepository $leaveRepository)
    {
        $this->leaveRepository = $leaveRepository;
    }

    public function index(Request $request)
    {
        $leaves = $this->leaveRepository->getLeaves($request);
        $users = $this->leaveRepository->getUsers();
        return view('admin.leaves.index', compact('leaves', 'users'));
    }

    public function export(Request $request)
    {
        return $this->leaveRepository->exportData($request);
    }
}
