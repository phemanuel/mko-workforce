<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    //
    public function verify(EmailVerificationRequest $request)
{
    if (!auth()->check()) {
        return redirect('/staff-login')
            ->with('error', 'Please login to verify your email.');
    }

    $request->fulfill();

    $user = auth()->user();

    $user->update([
        'registration_step' => 2
    ]);

    return redirect()->route('complete.application')
        ->with('success', 'Email verified successfully. Please continue your application.');
}
}
