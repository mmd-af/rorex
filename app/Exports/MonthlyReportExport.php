<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
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
            'Plus Day (Hour)',
            'Plus Night (Hour)',
            'Total Plus Work',
            'Delay Work (Minute)',
            'Early Exit (Minute)',
            'Daily Absence (Day)',
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
