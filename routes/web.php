<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\WorkLogWebController;
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

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::get('dashboard', function () { return view('dashboard');})->name('dashboard');

Route::middleware(['auth'])->group(function () {
    
    // Work Log Resource Routes
    Route::get('/work-log', [WorkLogWebController::class, 'index'])->name('work-log.index');
    Route::get('/work-log/create', [WorkLogWebController::class, 'create'])->name('work-log.create');
    Route::post('/work-log', [WorkLogWebController::class, 'store'])->name('work-log.store');
    Route::get('/work-log/{entry}', [WorkLogWebController::class, 'show'])->name('work-log.show');
    Route::get('/work-log/{entry}/edit', [WorkLogWebController::class, 'edit'])->name('work-log.edit');
    Route::put('/work-log/{entry}', [WorkLogWebController::class, 'update'])->name('work-log.update');
    Route::delete('/work-log/{entry}', [WorkLogWebController::class, 'destroy'])->name('work-log.destroy');

    // Leave Resource Routes
    Route::get('/leave', [LeaveController::class, 'index'])->name('leave.index');
    Route::get('/leave/create', [LeaveController::class, 'create'])->name('leave.create');
    Route::post('/leave', [LeaveController::class, 'store'])->name('leave.store');
    Route::get('/leave/{entry}', [LeaveController::class, 'show'])->name('leave.show');
    Route::get('/leave/{entry}/edit', [LeaveController::class, 'edit'])->name('leave.edit');
    Route::put('/leave/{entry}', [LeaveController::class, 'update'])->name('leave.update');
    Route::delete('/leave/{entry}', [LeaveController::class, 'destroy'])->name('leave.destroy');
});
