<?php

namespace App\Http\Controllers;

use App\Imports\YourImportClass;
use App\Models\DailyReport\DailyReport;
use App\Models\User\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class ExcelImportController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);
        $file = $request->file('file');
        $excel2 = Excel::toArray(new YourImportClass, $file);
        DB::beginTransaction();
        try {
            foreach ($excel2[0] as $key => $item) {
                if ($key === 0) {
                    continue;
                }
                $date = date('Y-m-d', strtotime($item[2]));
                $codStaff = (int)$item[0];
                $report = new DailyReport();
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
            DB::commit();
            return response()->json(['success' => true]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Exception: ' . $e->getMessage());
            return response()->json(['error' => 'Error in operation']);
        }
    }

    public function importUser(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        // Get the uploaded file
        $file = $request->file('file');
        $excel2 = Excel::toArray(new YourImportClass, $file);
        DB::beginTransaction();

        try {
            foreach ($excel2[0] as $key => $item) {
                if ($key === 0) {
                    continue;
                }
                $dataAderarii = date('Y-m-d', strtotime($item[7]));
                $dataNasterii = date('Y-m-d', strtotime($item[10]));
                $dataAbsolvirii = date('Y-m-d', strtotime($item[21]));
                $dataPlecarii = date('Y-m-d', strtotime($item[24]));
                $codStaff = (int)$item[0];
                $idUtilizator = (int)$item[1];
                $report = new User();
                $report->id = $codStaff;
                $report->id_utilizator = $idUtilizator;
                $report->name = $item[2];
                $report->departament = $item[3];
                $report->pozitie = $item[4];
                $report->numar_card = $item[5];
                $report->parola = $item[6];
                $report->data_aderarii = $dataAderarii;
                $report->sex = $item[8];
                $report->starea_civila = $item[9];
                $report->data_nasterii = $dataNasterii;
                $report->telefon = $item[11];
                $report->card_de_identitate = $item[13];
                $report->functie = $item[14];
                $report->tip_personal = $item[15];
                $report->cod_postal = $item[16];
                $report->status_politic = $item[17];
                $report->rezidenta = $item[18];
                $report->nationalitate = $item[19];
                $report->educatie = $item[20];
                $report->data_absolvirii = $dataAbsolvirii;
                $report->scoala = $item[22];
                $report->profesie = $item[23];
                $report->data_plecarii = $dataPlecarii;
                $report->prenumele_tatalui = $item[25];
                $report->adresa = $item[26];
                $report->email = $item[12];
                $report->save();
            }

            DB::commit();
            return response()->json(['success' => true]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Exception: ' . $e->getMessage());
            return response()->json(['error' => 'Error in operation']);
        }
    }
}
