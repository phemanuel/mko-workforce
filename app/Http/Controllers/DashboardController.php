<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $user = auth()->user() ;

        $userRole = $user->role_id;

        // check user role---------------

        // ---Admin
        if($userRole == 1){
           return view('dashboard.admin');
        }
        elseif($userRole == 2){
           return view('dashboard.supervisor');
        }
        elseif($userRole == 3){
           return view('dashboard.staff');
        }
        else{
        return redirect()->route('login')->with('error', 'We can access your role at the moment.');
        }
        
    }
}
