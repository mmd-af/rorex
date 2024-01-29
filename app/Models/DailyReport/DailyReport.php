<?php

namespace App\Models\DailyReport;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class DailyReport extends Model
{
    use HasFactory,
        SoftDeletes,
        DailyReportRelationships,
        DailyReportModifiers;

    protected $table = 'daily_reports';
}
