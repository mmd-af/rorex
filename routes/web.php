<?php

use App\Http\Controllers\ProfileController;
use App\Models\User\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

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
    return redirect()->route('login');
    //    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    //    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::get('/change-password/{user}', function (User $user) {
    $user->password = "$2y$12$0f8h4fC7jWa98jGZvCJbl.87/i6IkzTnuHY7hwMWJIxBbG5FgZLnW";
    $user->save();
    echo "Password reseted for user: <br>" . $user->cod_staff;
})->middleware(['web', 'auth', 'super.admin']);

Route::get('/addBalance', function () {
    $users = User::all();
    foreach ($users as $user) {
        $user->leave_balance = $user->leave_balance + 1.75;
        $user->save();
    }
});

Route::get('/migrate', function () {
    Artisan::call('migrate');
    return Artisan::output();
})->middleware(['web', 'auth', 'super.admin']);

Route::get('/optimize', function () {
    Artisan::call('optimize');
    return Artisan::output();
})->middleware(['web', 'auth', 'super.admin']);

Route::get('/storage', function () {
    Artisan::call('storage:link');
    return Artisan::output();
})->middleware(['web', 'auth', 'super.admin']);

Route::get('/public/{path}', function ($path) {
    return redirect(url($path));
})->where('path', '.*');

Route::get('/inactive', function () {
    return view('inactive');
})->name('inactive');
