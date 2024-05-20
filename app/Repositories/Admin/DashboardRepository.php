<?php

namespace App\Repositories\Admin;

use App\Models\LetterAssignment\LetterAssignment;
use App\Models\StaffRequest\StaffRequest;
use App\Models\Support\Support;
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
        $supports = Support::query()
            ->select([
                'id',
                'subject'
            ])
            ->where('organization', auth()->user()->getRoleNames())
            ->where('read_at', null)
            ->get();
        $qty = count($letter_assignments);
        $SupportQty = count($supports);
        return response()->json(['qty' => $qty + $SupportQty]);
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

        $supports = Support::query()
            ->select([
                'id',
                'subject'
            ])
            ->where('organization', auth()->user()->getRoleNames())
            ->where('read_at', null)
            ->get();
        return response()->json(['letter_assignments' => $letter_assignments, 'supports' => $supports]);
    }
}
