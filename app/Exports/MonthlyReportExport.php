<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;

class MonthlyReportExport implements FromArray, WithHeadings, WithStyles
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
            'ot_ore (Hour)',
            'plus_week_day (Hour)',
            'plus_week_night (Hour)',
            'plus_holiday_day (Hour)',
            'plus_holiday_night (Hour)',
            'Delay Work (Hour)',
            'Early Exit (Hour)',
            'Daily Absence (Day)',
            'concediu_ore',
            'Total (Hours)',
            'Unknown',
            'Default Shift (Hour)',
            'forgotPunch'
        ];
    }

    public function styles(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet)
    {
        $sheet->getStyle('A1:Z1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('D7D7D7');
    }
}
