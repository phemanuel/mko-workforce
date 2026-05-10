<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Employee;
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
            'phone_no' => 'required|string|max:20',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ]);

        // CHECK IF USER EXISTS
        $existingUser = User::where('email', $request->email)->first();

        /*
        |--------------------------------------------------------------------------
        | USER EXISTS BUT NOT VERIFIED
        |--------------------------------------------------------------------------
        */

        if ($existingUser && !$existingUser->email_verified_at) {

            Auth::login($existingUser);

            $existingUser->sendEmailVerificationNotification();

            return redirect()->route('email.verify')
                ->with(
                    'success',
                    'You already started registration. Verification email resent.'
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
        | CREATE USER
        |--------------------------------------------------------------------------
        */

        $user = User::create([
            'name' => $request->name,
            'phone_no' => $request->phone_no,
            'email' => $request->email,
            'role_id' => 3,
            'registration_step' => 1,
            'status' => 'pending',
            'password' => Hash::make($request->password),
        ]);

        /*
        |--------------------------------------------------------------------------
        | LOGIN USER
        |--------------------------------------------------------------------------
        */

        Auth::login($user);

        /*
        |--------------------------------------------------------------------------
        | CREATE EMPLOYEE RECORD
        |--------------------------------------------------------------------------
        */

        Employee::create([
            'user_id' => $user->id,
        ]);

        /*
        |--------------------------------------------------------------------------
        | SEND VERIFICATION EMAIL
        |--------------------------------------------------------------------------
        */

        $user->sendEmailVerificationNotification();

        return redirect()->route('email.verify')
            ->with(
                'success',
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
        | ATTEMPT LOGIN (ALWAYS REQUIRED)
        |--------------------------------------------------------------------------
        */
        if (!Auth::attempt($credentials)) {

            return back()->withErrors([
                'error' => 'Invalid login details.',
            ]);
        }

        $request->session()->regenerate();

        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | ADMIN & SUPERVISOR FLOW (ROLE 1 & 2)
        |--------------------------------------------------------------------------
        | Still authenticated users, but skip onboarding checks
        |--------------------------------------------------------------------------
        */
        if (in_array($user->role_id, [1, 2])) {

        log_activity(
            'employee_login',
            'Employee login',
            $user->name . '-' . $user->role_id . " successfully login"
        );

            return redirect()->route('dashboard');
        }

        /*
        |--------------------------------------------------------------------------
        | STAFF FLOW (ROLE 3 ONLY)
        |--------------------------------------------------------------------------
        */
        if ($user->role_id == 3) {

            /*
            |--------------------------------------------------------------
            | EMAIL NOT VERIFIED
            |--------------------------------------------------------------
            */
            if ($user->registration_step == 1) {

                $user->sendEmailVerificationNotification();

                // Auth::logout();

                return redirect()->route('email.verify')
                    ->with('success', 'Please verify your email.');
            }

            /*
            |--------------------------------------------------------------
            | COMPLETED BUT NOT APPROVED
            |--------------------------------------------------------------
            */
            if ($user->status === 'active' && $user->approval_status === 'pending') {

                Auth::logout();

                return back()->withErrors([
                    'error' => 'Your application is awaiting admin approval.'
                ]);
            }

            /*
            |--------------------------------------------------------------
            | REJECTED
            |--------------------------------------------------------------
            */
            if ($user->approval_status === 'rejected') {

                Auth::logout();

                return back()->withErrors([
                    'error' => 'Your application was not approved.'
                ]);
            }

            /*
            |--------------------------------------------------------------
            | APPROVED STAFF
            |--------------------------------------------------------------
            */
            if ($user->approval_status === 'approved') {
                log_activity(
                    'staff_login',
                    'Staff login',
                    $user->name . '-' . $user->role_id . " successfully login"
                );

                return redirect()->route('dashboard');
            }

            /*
            |--------------------------------------------------------------
            | ONBOARDING IN PROGRESS
            |--------------------------------------------------------------
            */
            if ($user->registration_step >= 2 && $user->registration_step < 6) {

                return redirect()->route('complete.application')
                    ->with('success', 'Please continue your application.');
            }

            /*
            |--------------------------------------------------------------
            | FALLBACK SAFETY
            |--------------------------------------------------------------
            */
            Auth::logout();

            return back()->withErrors([
                'error' => 'Unable to determine application status.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | UNKNOWN ROLE SAFETY
        |--------------------------------------------------------------------------
        */
        Auth::logout();

        return back()->withErrors([
            'error' => 'Unauthorized role detected.'
        ]);
    }
    /**
     * LOGOUT USER
     */
    public function logout(Request $request)
    {
        $user = auth()->user();
        log_activity(
            'staff_logout',
            'Staff logout',
            $user->name . '-' . $user->role_id . " successfully logged out."
        );
        Auth::logout();  

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}