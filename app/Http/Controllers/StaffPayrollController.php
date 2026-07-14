<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Payroll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffPayrollController extends Controller
{
    //

    /**
     * Payroll Dashboard
     */
    public function index()
    {
        $employee = Employee::where('user_id', Auth::id())->firstOrFail();

        $payrolls = Payroll::with('items')
            ->where('employee_id', $employee->id)
            ->latest('period_end')
            ->paginate(10);

        // Statistics
        $totalEarned = Payroll::where('employee_id', $employee->id)
            ->where('status', Payroll::STATUS_PAID)
            ->sum('net_pay');

        $paidPayrolls = Payroll::where('employee_id', $employee->id)
            ->where('status', Payroll::STATUS_PAID)
            ->count();

        $pendingAmount = Payroll::where('employee_id', $employee->id)
            ->whereIn('status', [
                Payroll::STATUS_DRAFT,
                Payroll::STATUS_APPROVED,
            ])
            ->sum('net_pay');

        $thisMonth = Payroll::where('employee_id', $employee->id)
            ->whereMonth('period_end', now()->month)
            ->whereYear('period_end', now()->year)
            ->first();

        return view('staff.payroll.index', compact(
            'employee',
            'payrolls',
            'totalEarned',
            'paidPayrolls',
            'pendingAmount',
            'thisMonth'
        ));
    }

    /**
     * Payroll Inspector
     */
    public function show(Payroll $payroll)
    {
        abort_unless(
            $payroll->employee->user_id === Auth::id(),
            403
        );

        $payroll->load([
            'items.shift',
            'generatedBy',
            'approvedBy',
            'paidBy',
        ]);

        return response()->json($payroll);
    }

    /**
     * Download Payslip
     */
    public function download(Payroll $payroll)
    {
        abort_unless(
            $payroll->employee->user_id === Auth::id(),
            403
        );

        // PDF generation comes later.
    }
}
