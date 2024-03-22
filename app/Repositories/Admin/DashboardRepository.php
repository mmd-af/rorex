<?php

namespace App\Repositories\Admin;

use App\Models\LetterAssignment\LetterAssignment;
use App\Models\StaffRequest\StaffRequest;
use Illuminate\Support\Facades\Auth;

class DashboardRepository extends BaseRepository
{
    public function checkNewNotification($request)
    {
        $letter_assignments = LetterAssignment::query()
            ->select([
                'id',
                'request_id',
                'is_archive'
            ])
            ->where('is_archive', 0)
            ->where('assigned_to', Auth::id())
            ->with('request')
            ->get();
        $qty = count($letter_assignments);
        return response()->json(['qty' => $qty]);
    }

    public function getNewNotifications($request)
    {
        $letter_assignments = LetterAssignment::query()
            ->select([
                'id',
                'request_id',
                'is_archive'
            ])
            ->where('is_archive', 0)
            ->where('assigned_to', Auth::id())
            ->with('request')
            ->get();
        return response()->json(['letter_assignments' => $letter_assignments]);
    }
}
