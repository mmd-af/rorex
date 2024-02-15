<?php

namespace App\Models\DailyReport;

use App\Models\User\User;

trait DailyReportRelationships
{
    public function users()
    {
        return $this->belongsTo(User::class, 'cod_staff');
    }
}
