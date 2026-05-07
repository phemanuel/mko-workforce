<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Mail\WelcomeVerificationMail;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function home()
    {
        return view('auth.login');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function register()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ]);

        // CHECK IF USER ALREADY EXISTS
        $existingUser = User::where('email', $request->email)->first();

        /*
        |--------------------------------------------------------------------------
        | USER EXISTS BUT NOT VERIFIED
        |--------------------------------------------------------------------------
        */

        if ($existingUser && !$existingUser->email_verified_at) {

            // RESEND VERIFICATION EMAIL
            $existingUser->sendEmailVerificationNotification();

            return redirect()->route('email.verify')
                ->with('success',
                    'You already started registration. Verification email has been resent.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | USER EXISTS AND VERIFIED
        |--------------------------------------------------------------------------
        */

        if ($existingUser && $existingUser->email_verified_at) {

            return back()->withErrors([
                'email' => 'This email is already registered. Please login instead.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | CREATE NEW USER
        |--------------------------------------------------------------------------
        */

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'role_id' => 3, // STAFF
            'registration_step' => 1,
            'status' => 'pending',
            'password' => Hash::make($request->password),
        ]);

        /*
        |--------------------------------------------------------------------------
        | SEND VERIFICATION EMAIL
        |--------------------------------------------------------------------------
        */

        $user->sendEmailVerificationNotification();

        return redirect()->route('email.verify')
            ->with('success',
                'Verification email sent successfully. Please check your inbox.'
            );
    }

    public function loginAction(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDATE INPUT
        |--------------------------------------------------------------------------
        */

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | ATTEMPT LOGIN
        |--------------------------------------------------------------------------
        */

        if (!Auth::attempt($credentials)) {

            return back()->withErrors([
                'error' => 'Invalid login details.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | USER LOGGED IN
        |--------------------------------------------------------------------------
        */

        $request->session()->regenerate();

        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | STEP 1
        | ACCOUNT CREATED BUT EMAIL NOT VERIFIED
        |--------------------------------------------------------------------------
        */

        if ($user->registration_step == 1) {

            // RESEND VERIFICATION EMAIL
            $user->sendEmailVerificationNotification();

            // Auth::logout();

            return redirect()->route('email.verify')
                ->with('success',
                    'Your email is not verified yet. A new verification email has been sent.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | STEP 2
        | EMAIL VERIFIED - COMPLETE APPLICATION
        |--------------------------------------------------------------------------
        */

        if ($user->registration_step == 2) {

            return redirect()->route('application.form')
                ->with('success',
                    'Please complete your application form.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | STEP 3
        | APPLICATION STARTED BUT NOT SUBMITTED
        |--------------------------------------------------------------------------
        */

        if ($user->registration_step == 3) {

            return redirect()->route('application.form')
                ->with('success',
                    'Continue your application.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | STEP 4
        | APPLICATION SUBMITTED
        |--------------------------------------------------------------------------
        */

        if ($user->registration_step == 4) {

            Auth::logout();

            return back()->withErrors([
                'error' => 'Your application has been submitted and is awaiting review.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | STEP 5
        | UNDER REVIEW
        |--------------------------------------------------------------------------
        */

        if ($user->registration_step == 5) {

            Auth::logout();

            return back()->withErrors([
                'error' => 'Your application is currently under review.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | STEP 6
        | APPROVED USERS
        |--------------------------------------------------------------------------
        */

        if ($user->registration_step == 6) {

            return redirect()->route('dashboard');
        }

        /*
        |--------------------------------------------------------------------------
        | STEP 7
        | REJECTED USERS
        |--------------------------------------------------------------------------
        */

        if ($user->registration_step == 7) {

            Auth::logout();

            return back()->withErrors([
                'error' => 'Your application was not approved. Please contact support.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | FALLBACK
        |--------------------------------------------------------------------------
        */

        Auth::logout();

        return back()->withErrors([
            'error' => 'Unable to determine account status.'
        ]);
    }
    /**
     * LOGOUT USER
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}