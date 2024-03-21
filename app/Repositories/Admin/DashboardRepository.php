<?php

namespace App\Repositories\Admin;

use App\Models\StaffRequest\StaffRequest;

class DashboardRepository extends BaseRepository
{
    public function checkNewNotification($request)
    {
        $staffRequests = StaffRequest::query()
            ->select([
                'id',
                'read_at'
            ])
            ->where('read_at', null)
            ->get();
        $staffRequests = count($staffRequests);
        return response()->json(['qty' => $staffRequests]);
    }
}
