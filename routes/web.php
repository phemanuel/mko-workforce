<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DashboardController;


    Route::get('/', [AuthController::class, 'home'])
    ->name('home');

    Route::get('/staff-login', [AuthController::class, 'showLogin'])
    ->name('login');

    Route::post('/staff-login', [AuthController::class, 'loginAction'])
    ->name('login.submit');

    Route::get('/register', [AuthController::class, 'register'])
        ->name('register');

    Route::post('/register', [AuthController::class, 'store'])
    ->name('register.save');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    Route::middleware(['auth', 'approved'])->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

        // Admin recruitment dashboard
        Route::get('/admin/employees', [EmployeeController::class, 'index']);

        Route::get('/admin/employees/{id}', [EmployeeController::class, 'show']);

        Route::post('/admin/employees/{id}/approve', [EmployeeController::class, 'approve']);

        Route::post('/admin/employees/{id}/reject', [EmployeeController::class, 'reject']);


});
