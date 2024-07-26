<?php

namespace App\Models\DailyReport;

use App\Models\User\User;

trait DailyReportRelationships
{
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function editBy()
    {
        return $this->belongsTo(User::class, 'edit_by');
    }
}
