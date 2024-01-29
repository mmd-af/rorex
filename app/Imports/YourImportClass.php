<?php

namespace App\Imports;

use App\Models\Report;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class YourImportClass implements ToCollection
{

    public function collection(Collection $rows)
    {
//        foreach ($rows as $row) {
//          $this->where('Cod staff', $row[0])->first();
//        }
//        foreach ($rows[0] as $item) {
//            $report = new Report();
//            $report->cod_staff = $item[0][0];
//            $report->nume = $item[0][1];
//            $report->on_work = $item[0][5];
//            $report->save();
//            echo $item;
//        }
    }
}
