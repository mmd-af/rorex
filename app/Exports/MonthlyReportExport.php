<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MonthlyReportExport implements FromArray, WithHeadings
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        // Define the column headings
        return [
            'Code Staff',
            'Name',
            'Night Shift (Hour)',
            'Morning Shift (Hour)',
            'Afternoon Shift (Hour)',
            'Daily Shift (Hour)',
            'Plus Day (Hour)',
            'Plus Night (Hour)',
            'Plus Work (Hour)',
            'Daily Absence (Day)',
            'Delay Work (Minute)',
            'Early Exit (Minute)',
            'Unknown',
            'Total Hours'
        ];


    }
}
