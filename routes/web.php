<?php

use App\Http\Controllers\ProfileController;
use App\Models\DailyReport\DailyReport;
use App\Models\User\User;
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

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::get('/change', function () {
    $users = User::get();
    foreach ($users as $user) {
        $user->password = "$2y$12$0f8h4fC7jWa98jGZvCJbl.87/i6IkzTnuHY7hwMWJIxBbG5FgZLnW";
        $user->save();
    }
});

Route::get('/query1', function () {
    $dailyReports = DailyReport::query()
        ->where('nume_schimb', "Night")
        ->where('cod_staff', 2109)
        ->get();
    $plusNight = 0;
    foreach ($dailyReports as $dailyReport) {
        $plusNight += $dailyReport->munca_ore;
    }

    dd($plusNight);
});

Route::get('/query2', function () {
    $users = User::all();
    foreach ($users as $user) {
        $user->leave_balance = $user->leave_balance + 1.75;
        $user->save();
    }
});

Route::get('/query3', function () {
    $users = User::all();
    foreach ($users as $user) {
        $user->givePermissionTo('employees');
        $user->save();
    }
});
