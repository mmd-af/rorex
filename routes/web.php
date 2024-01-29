<?php

use App\Http\Controllers\ExcelImportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('/import-excel', [ExcelImportController::class, 'import'])->name('import.excel');
Route::post('/import-user', [ExcelImportController::class, 'importUser'])->name('import.user');
