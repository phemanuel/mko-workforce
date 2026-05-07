<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\ApplicationController;



        /*
    |--------------------------------------------------------------------------
    | SHOW EMAIL VERIFY PAGE
    |--------------------------------------------------------------------------
    */
    Route::get('/email/verify', function () {
        return view('auth.email_verify');
    })->middleware('auth')->name('email.verify');

    /*
    |--------------------------------------------------------------------------
    | HANDLE EMAIL VERIFICATION LINK CLICK
    |--------------------------------------------------------------------------
    */
    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
        ->middleware(['auth', 'signed'])
        ->name('verification.verify');     

    /*
    |--------------------------------------------------------------------------
    | RESEND VERIFICATION EMAIL
    |--------------------------------------------------------------------------
    */
    Route::post('/email/verification-notification', function (Request $request) {

        $request->user()->sendEmailVerificationNotification();

        return back()->with('message', 'Verification link sent!');
    })
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');

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

        Route::middleware(['auth'])->group(function () {
            Route::get('/complete-application', [ApplicationController::class, 'index'])
            ->name('complete.application');
        });

    Route::middleware(['auth', 'verified'])->group(function () {

        Route::get('/application', [ApplicationController::class, 'index'])
            ->name('application.index');

        Route::post('/application/step2', [ApplicationController::class, 'step2'])
            ->name('application.step2');

        Route::post('/application/step3', [ApplicationController::class, 'step3'])
            ->name('application.step3');

        Route::post('/application/step4', [ApplicationController::class, 'step4'])
            ->name('application.step4');

        Route::post('/application/step5', [ApplicationController::class, 'step5'])
            ->name('application.step5');

        Route::post('/application/step6', [ApplicationController::class, 'step6'])
            ->name('application.step6');

        Route::post('/application/submit', [ApplicationController::class, 'submit'])
            ->name('application.submit');
        
        Route::get('/application/edit/{step}', [ApplicationController::class, 'editStep'])
        ->name('application.edit.step');
    });

    Route::middleware(['auth', 'approved'])->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');        

});
