<?php

namespace App\Http\Controllers\Admin\Leave;

use App\Http\Controllers\Controller;
use App\Models\Leave\Leave;
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
        $leaveTypes = Leave::distinct('type')->pluck('type');
        return view('admin.leaves.index', compact('leaves', 'users', 'leaveTypes'));
    }

    public function export(Request $request)
    {
        return $this->leaveRepository->exportData($request);
    }
}
