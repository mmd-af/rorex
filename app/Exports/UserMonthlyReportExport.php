<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;

class UserMonthlyReportExport implements FromArray, WithHeadings, WithStyles
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
        return [
            'Code Staff',
            'Name',
            'Night Shift (Hour)',
            'Morning Shift (Hour)',
            'Afternoon Shift (Hour)',
            'Daily Shift (Hour)',
            'plus_week_day (Hour)',
            'plus_week_night (Hour)',
            'plus_holiday_day (Hour)',
            'plus_holiday_night (Hour)',
            'Compensation (Hour)',
            'Daily Absence (Day)',
            'Allowed Leave',
            'without_paid_leave',
            'Total (Hours)',
            'Unknown',
            'Default Shift (Hour)'
        ];
    }

    public function styles(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet)
    {
        $sheet->getStyle('A1:Z1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('D7D7D7');
    }
}
