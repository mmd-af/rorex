<?php

namespace App\Http\Controllers;

use App\Imports\YourImportClass;
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
        $data = Excel::toCollection(new YourImportClass, $file);
        $data=$data->toArray();
//        $data=collect($data);
        return view('welcome', compact($data));
    }
}
