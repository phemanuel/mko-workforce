<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Employee;
use App\Models\EmployeeProfile;
use App\Models\EmployeeWorkEligibility;
use App\Models\Skill;
use App\Models\EmployeeRoleDetail;
use App\Models\EmployeePayroll;
use App\Models\EmployeeDocument;
use App\Models\Activity;

class DashboardController extends Controller
{
    //
    public function index()
   {
      $user = auth()->user();
      $userRole = $user->role_id;
      $applications = User::where('role_id', 3)
      ->with('employee')
      ->latest()
      ->take(5)
      ->get();

      /*
      |--------------------------------------------------------------------------
      | ADMIN DASHBOARD
      |--------------------------------------------------------------------------
      */
      if ($userRole == 1) {

         $data = [
            'totalEmployees' => Employee::count(),
            'activeEmployees' => User::where('approval_status', 'approved')->count(),
            'pendingApprovals' => User::where('role_id', 3)->where('approval_status', 'pending')->count(),
            'complianceAlerts' => EmployeeDocument::whereNotNull('expiry_date')
               ->whereDate('expiry_date', '<=', now()->addDays(30))
               ->where('verification_status', 'Verified')
               ->count(),

            'applications' => $applications, // 
            'activities' => Activity::latest()->take(5)->get(),
            
         ];

         return view('dashboard.admin', $data);
      }

      /*
      |--------------------------------------------------------------------------
      | SUPERVISOR DASHBOARD
      |--------------------------------------------------------------------------
      */
      elseif ($userRole == 2) {

         return view('dashboard.supervisor');
      }

      /*
      |--------------------------------------------------------------------------
      | STAFF DASHBOARD
      |--------------------------------------------------------------------------
      */
      elseif ($userRole == 3) {

         return view('dashboard.staff');
      }

      /*
      |--------------------------------------------------------------------------
      | FALLBACK
      |--------------------------------------------------------------------------
      */
      return redirect()->route('login')
         ->with('error', 'We cannot access your role at the moment.');
   }


}
