<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;

class FullMonthlyReportExport implements FromArray, WithHeadings, WithStyles
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
            'Department',
            'Night Shift (Hour)',
            'Morning Shift (Hour)',
            'Afternoon Shift (Hour)',
            'Daily Shift (Hour)',
            'Compensation',
            'plus_week_day (Hour)',
            'plus_week_night (Hour)',
            'plus_holiday_day (Hour)',
            'plus_holiday_night (Hour)',
            // 'total minus work(Hour)',
            'Daily Absence (Day)',
            'without Paid Leave',
            'Allowed Leave',
            'Total (Hours)'
        ];
    }

    public function styles(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet)
    {
        $sheet->getStyle('A1:Z1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('D7D7D7');
    }
}
