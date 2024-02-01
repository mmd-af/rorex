<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class DailyReportExcel implements ToCollection
{
    public function collection(Collection $rows)
    {
    }
}
