<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ScheduleController;
use App\Models\User;

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->controller(ScheduleController::class)->prefix('schedules')->name('schedules.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/', 'store')->name('store');
    Route::get('/create', 'create')->name('create');
    
});

Route::controller(AdminController::class)->middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/users', 'users')->name('users');
    Route::post('/user/{id}/role', 'updateRole')->name('updateRole');
    
});

Route::controller(ScheduleController::class)->middleware(['auth', 'role:admin'])->prefix('admin/schedules')->name('admin.schedules.')->group(function () {
    Route::get('/', 'indexAdmin');
    Route::post('/', 'store');
    Route::put('/{scheduleId}', 'update');
    Route::delete('/{scheduleId}', 'destroy');
    Route::post('/{schedule}/accept', 'accept')->name('accept');
    Route::post('/{schedule}/reject', 'reject')->name('reject');
});


Route::controller(ScheduleController::class)->group(function () {
    Route::get('/', 'index');
});



