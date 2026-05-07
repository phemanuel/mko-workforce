<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserApproved
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle($request, Closure $next)
    {
        if (auth()->check()) {

            if (auth()->user()->approval_status !== 'approved') {

                Auth::logout();

                return redirect('/staff-login')
                    ->with('error', 'Your account is pending approval.');
            }
        }

        return $next($request);
    }
}
