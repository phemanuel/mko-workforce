<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed'
        ]);

        $staffRole = Role::where('name', 'staff')->first();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => Role::where('name', 'staff')->first()->id,
            'status' => 'pending'
        ]);

        // AUTO CREATE EMPLOYEE PROFILE
        $user->employee()->create([
            'status' => 'pending'
        ]);

        Auth::login($user);

        return redirect('/dashboard');
    }

    public function loginAction(Request $request)
    {
        // 1. Validate input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Attempt login
        if (Auth::attempt($credentials)) {

            $user = Auth::user();

            // 3. Check if user is approved
            if ($user->status !== 'approved') {

                Auth::logout();

                return back()->withErrors([
                    'email' => 'Your account is pending approval.',
                ]);
            }

            // 4. Regenerate session
            $request->session()->regenerate();

            // 5. Redirect to dashboard
            return redirect()->route('dashboard');
        }

        // 6. Failed login
        return back()->withErrors([
            'error' => 'Invalid login details.',
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