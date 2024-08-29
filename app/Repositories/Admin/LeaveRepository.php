<?php

namespace App\Repositories\Admin;

use App\Models\Leave\Leave;
use App\Models\User\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\LeavesExport;

class LeaveRepository extends BaseRepository
{
    public function __construct(Leave $model)
    {
        $this->setModel($model);
    }

    public function getLeaves($request)
    {
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $userId = $request->input('user_id');
        $query = $this->query();
        if ($fromDate) {
            $query->where('start_date', '>=', $fromDate);
        }
        if ($toDate) {
            $query->where('end_date', '<=', $toDate);
        }
        if ($userId) {
            $query->where('user_id', $userId);
        }
        $leaves = $query->get()->load('users.employee');
        foreach ($leaves as $leave) {
            $leave->formatted_start_date = $this->formatDate($leave->start_date, $leave->leave_days);
            $leave->formatted_end_date = $this->formatDate($leave->end_date, $leave->leave_days);
            $leave->formatted_leave_value = $this->formatLeaveValue($leave);
        }
        return $leaves;
    }

    private function formatDate($date, $leaveDays)
    {
        $originalDate = Carbon::parse($date);
        return $leaveDays ? $originalDate->format('Y-m-d') : $originalDate->format('Y-m-d H:i');
    }

    private function formatLeaveValue($leave)
    {
        if ($leave->leave_time) {
            $originalTime = Carbon::parse($leave->leave_time)->format('H:i');
            return $originalTime . " hour";
        }
        if ($leave->leave_days) {
            return $leave->leave_days . " days";
        }
        return 'No leave data';
    }

    public function getUsers()
    {
        return User::query()
            ->select([
                'id'
            ])
            ->has('employee')
            ->get();
    }

    public function exportData($request)
    {
        $format = $request->input('format');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $userId = $request->input('user_id');
        $query = $this->query();
        if ($fromDate) {
            $query->where('start_date', '>=', $fromDate);
        }
        if ($toDate) {
            $query->where('end_date', '<=', $toDate);
        }
        if ($userId) {
            $query->where('user_id', $userId);
        }

        $leaves = $query->get()->load('users.employee');

        foreach ($leaves as $leave) {
            $leave->formatted_start_date = $this->formatDate($leave->start_date, $leave->leave_days);
            $leave->formatted_end_date = $this->formatDate($leave->end_date, $leave->leave_days);
            $leave->formatted_leave_value = $this->formatLeaveValue($leave);
        }

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('admin.leaves.pdf', compact('leaves'));
            return $pdf->download("leaves_report-" . rand(0000, 9999) . ".pdf");
        }

        if ($format === 'excel') {
            $data = $leaves->map(function ($leave) {
                return [
                    'Tracking Number' => $leave->id . "/ " . $leave->request_id,
                    'User Name' => $leave->users->employee->last_name . " " . $leave->users->employee->first_name,
                    'Start Date' => $leave->formatted_start_date,
                    'End Date' => $leave->formatted_end_date,
                    'Type' => $leave->type,
                    'Leave Value' => $leave->formatted_leave_value
                ];
            })->toArray();
            return Excel::download(new LeavesExport($data), "leaves_report-" . rand(0000, 9999) . ".xlsx");
        }
    }
}
