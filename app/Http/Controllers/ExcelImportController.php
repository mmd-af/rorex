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
//        $data = Excel::toCollection(new YourImportClass, $file);
//        foreach ($data as $dat) {
//            foreach ($dat as $da) {
//                dd($da);
//            }
//        }
        $excel1 = Excel::import(new YourImportClass, $file);
        $excel2 = Excel::toArray(new YourImportClass, $file);
        foreach ($excel2[0] as $item) {
            $report = new Report();
            $report->cod_staff = $item[0];
            $report->nume = $item[1];
            $report->on_work = $item[5];
            $report->save();
        }
        dd($excel1, $excel2);

//        dd("خطا");
        return view('welcome');
    }
}
