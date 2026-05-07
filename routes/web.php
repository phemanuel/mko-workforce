<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DashboardController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;


    Route::get('/email/verify', function () {
        return view('auth.email_verify');
    })->middleware('auth')->name('email.verify');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();

        return redirect('/complete-application');
    })->middleware(['auth', 'signed'])->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('message', 'Verification link sent!');
    })->middleware(['auth', 'throttle:6,1'])->name('verification.send');

    // ------------------------------------------------------------

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

        Route::get('/apply', [AuthController::class, 'register']);
Route::post('/apply', [AuthController::class, 'store']);

        // Admin recruitment dashboard
        Route::get('/admin/employees/create', [EmployeeController::class, 'create'])
            ->name('employees.create');

        Route::post('/admin/employees', [EmployeeController::class, 'store'])
            ->name('employees.store');


});
