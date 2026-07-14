<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Payroll;
use Illuminate\Support\Facades\DB;

class PayrollController extends Controller
{
    //

    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | Employees Eligible For Payroll
        |--------------------------------------------------------------------------
        */

        $employees = Employee::with('user')
        ->orderBy('employee_number')
        ->get();

        $eligibleEmployees = Employee::whereHas('attendances', function ($query) {

            $query->whereIn('status', [
                'Checked Out',
                'Completed',
                'Early Leave',
            ])
            ->whereDoesntHave('payrollItem');

        })->count();

        /*
        |--------------------------------------------------------------------------
        | Pending Earnings
        |--------------------------------------------------------------------------
        */

        $pendingEarnings = Attendance::whereIn('status', [
                'Checked Out',
                'Completed',
                'Early Leave',
            ])
            ->whereDoesntHave('payrollItem')
            ->with('shift')
            ->get()
            ->sum(function ($attendance) {

                return ($attendance->worked_hours ?? 0)
                    * ($attendance->shift->hourly_rate ?? 0);

            });

        /*
        |--------------------------------------------------------------------------
        | Payroll Statistics
        |--------------------------------------------------------------------------
        */

        $draftPayrolls = Payroll::where('status', Payroll::STATUS_DRAFT)->count();

        $approvedPayrolls = Payroll::where('status', Payroll::STATUS_APPROVED)->count();

        $paidPayrolls = Payroll::where('status', Payroll::STATUS_PAID)->count();

        $paidThisMonth = Payroll::where('status', Payroll::STATUS_PAID)
            ->whereMonth('payment_date', now()->month)
            ->whereYear('payment_date', now()->year)
            ->sum('net_pay');

        /*
        |--------------------------------------------------------------------------
        | Recent Payrolls
        |--------------------------------------------------------------------------
        */

        $recentPayrolls = Payroll::with('employee.user')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.payroll.index', compact(

           'eligibleEmployees',
            'pendingEarnings',
            'draftPayrolls',
            'approvedPayrolls',
            'paidPayrolls',
            'paidThisMonth',
            'recentPayrolls',
            'employees'

        ));
    }


}
