<?php

namespace App\Http\Controllers;

use App\Imports\YourImportClass;
use App\Models\Report;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExcelImportController extends Controller
{
    public function import(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        // Get the uploaded file
        $file = $request->file('file');
        $excel2 = Excel::toArray(new YourImportClass, $file);
        foreach ($excel2[0] as $item) {
            $date = date('Y-m-d', strtotime($item[2]));
            $codStaff = (int)$item[0];
            $report = new Report();
            $report->cod_staff = $codStaff;
            $report->nume = $item[1];
            $report->data = $date;
            $report->saptamana = $item[3];
            $report->nume_schimb = $item[4];
            $report->on_work1 = $item[5];
            $report->off_work1 = $item[6];
            $report->on_work2 = $item[7];
            $report->off_work2 = $item[8];
            $report->on_work3 = $item[9];
            $report->off_work3 = $item[10];
            $report->absenta_zile = $item[11];
            $report->munca_ore = $item[12];
            $report->ot_ore = $item[13];
            $report->tarziu_minute = $item[14];
            $report->devreme_minute = $item[15];
            $report->lipsa_ceas_timpi = $item[16];
            $report->sarbatoare_publica_ore = $item[17];
            $report->concediu_ore = $item[18];
            $report->remarca = $item[19];
            $report->save();
        }
        dd($excel2);

//        dd("خطا");
        return view('welcome');
    }
}
