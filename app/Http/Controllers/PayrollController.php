<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Payroll;
use App\Models\PayrollItem;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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
        ->orderBy('user_id')
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

    public function show(Payroll $payroll)
    {
        $payroll->load([

            'employee.user',

            'items',

            'generatedBy',

            'approvedBy',

            'paidBy',

        ]);

        return response()->json([

            /*
            |--------------------------------------------------------------------------
            | Basic
            |--------------------------------------------------------------------------
            */

            'id' => $payroll->id,

            'payroll_number' => $payroll->payroll_number,

            'status' => $payroll->status,

            /*
            |--------------------------------------------------------------------------
            | Period
            |--------------------------------------------------------------------------
            */

            'period_start' => optional($payroll->period_start)->format('d M Y'),

            'period_end' => optional($payroll->period_end)->format('d M Y'),

            'payment_date' => optional($payroll->payment_date)->format('d M Y'),

            'payment_reference' => $payroll->payment_reference,

            /*
            |--------------------------------------------------------------------------
            | Employee
            |--------------------------------------------------------------------------
            */

            'employee' => [

                'name' => optional($payroll->employee->user)->name,

                'employee_number' => $payroll->employee->employee_number,

                'role' => optional($payroll->employee->role)->name,

                'supervisor' => optional($payroll->employee->supervisor?->user)->name,

            ],

            /*
            |--------------------------------------------------------------------------
            | Earnings
            |--------------------------------------------------------------------------
            */

            'total_shifts' => $payroll->total_shifts,

            'total_hours' => $payroll->total_hours,

            'gross_pay' => $payroll->gross_pay,

            'allowance' => $payroll->allowance,

            'bonus' => $payroll->bonus,

            'tax' => $payroll->tax,

            'deduction' => $payroll->deduction,

            'net_pay' => $payroll->net_pay,

            /*
            |--------------------------------------------------------------------------
            | Audit
            |--------------------------------------------------------------------------
            */

            'generated_by' => optional($payroll->generatedBy)->name,

            'generated_at' => optional($payroll->created_at)
                ->format('d M Y h:i A'),

            'approved_by' => optional($payroll->approvedBy)->name,

            'approved_at' => optional($payroll->approved_at)
                ?->format('d M Y h:i A'),

            'paid_by' => optional($payroll->paidBy)->name,

            'paid_at' => optional($payroll->paid_at)
                ?->format('d M Y h:i A'),

            'remarks' => $payroll->remarks,

            /*
            |--------------------------------------------------------------------------
            | Buttons
            |--------------------------------------------------------------------------
            */

            'approve_url' => route('admin.payroll.approve', $payroll),

            'mark_paid_url' => route('admin.payroll.markPaid', $payroll),

            /*
            |--------------------------------------------------------------------------
            | Payroll Items
            |--------------------------------------------------------------------------
            */

            'items' => $payroll->items->map(function ($item) {

                return [

                    'shift' => $item->shift_title,

                    'date' => optional($item->shift_date)
                        ->format('d M Y'),

                    'hours' => number_format($item->hours_worked, 2),

                    'rate' => $item->hourly_rate,

                    'amount' => $item->amount,

                ];

            }),

        ]);
    }

    public function preview(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validate
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'start_date' => ['required', 'date'],
            'end_date'   => ['required', 'date', 'after_or_equal:start_date'],
        ]);

        $startDate = Carbon::parse($validated['start_date'])->startOfDay();

        $endDate = Carbon::parse($validated['end_date'])->endOfDay();

        /*
        |--------------------------------------------------------------------------
        | Completed Attendances
        |--------------------------------------------------------------------------
        */

        $attendances = Attendance::with([
            'employee.user',
            'shift',
        ])
        ->whereBetween('check_out_time', [$startDate, $endDate])
        ->whereIn('status', [
            'Completed',
            'Checked Out',
        ])
        ->get();

        if ($attendances->isEmpty()) {

            return response()->json([
                'message' => 'No completed attendance records found for the selected period.'
            ], 422);

        }

        /*
        |--------------------------------------------------------------------------
        | Group By Employee
        |--------------------------------------------------------------------------
        */

        $employees = [];

        $grossPayroll = 0;

        foreach ($attendances->groupBy('employee_id') as $employeeId => $records) {

            $employee = $records->first()->employee;

            $totalHours = 0;

            $totalShifts = $records->count();

            $grossPay = 0;

            $items = [];

            /*
            |--------------------------------------------------------------------------
            | Existing Payroll?
            |--------------------------------------------------------------------------
            */

            $existingPayroll = Payroll::where('employee_id', $employeeId)
                ->whereDate('period_start', $startDate)
                ->whereDate('period_end', $endDate)
                ->exists();

            /*
            |--------------------------------------------------------------------------
            | Attendance Breakdown
            |--------------------------------------------------------------------------
            */

            foreach ($records as $attendance) {

                $hours = $attendance->worked_hours ?? 0;

                $rate = $attendance->shift->hourly_rate ?? 0;

                $amount = $hours * $rate;

                $grossPay += $amount;

                $totalHours += $hours;

                $items[] = [

                    'attendance_id' => $attendance->id,

                    'shift_id' => $attendance->shift_id,

                    'shift_title' => $attendance->shift->title,

                    'shift_date' => optional($attendance->shift->shift_date)
                        ->format('d M Y'),

                    'worked_hours' => round($hours, 2),

                    'hourly_rate' => round($rate, 2),

                    'amount' => round($amount, 2),

                ];

            }

            $grossPayroll += $grossPay;

            $employees[] = [

                'employee_id' => $employee->id,

                'employee_role' => $employee->primary_role,

                'name' => optional($employee->user)->name,

                'total_shifts' => $totalShifts,

                'total_hours' => round($totalHours, 2),

                'gross_pay' => round($grossPay, 2),

                'status' => $existingPayroll ? 'Existing' : 'New',

                'items' => $items,

            ];

        }

        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'summary' => [

                'period_start' => $startDate->format('d M Y'),

                'period_end' => $endDate->format('d M Y'),

                'employees' => count($employees),

                'completed_shifts' => $attendances->count(),

                'hours' => round($attendances->sum('worked_hours'), 2),

                'gross_pay' => round($grossPayroll, 2),

                'existing_payrolls' => collect($employees)
                    ->where('status', 'Existing')
                    ->count(),

                'new_payrolls' => collect($employees)
                    ->where('status', 'New')
                    ->count(),

            ],

            'employees' => $employees,

        ]);

    }

    public function generate(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validate Request
        |--------------------------------------------------------------------------
        |
        | Validate the selected payroll period before processing.
        | Both dates are required and the end date cannot be before the start date.
        |
        */

        $validated = $request->validate([

            'start_date' => [
                'required',
                'date',
            ],

            'end_date' => [
                'required',
                'date',
                'after_or_equal:start_date',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Parse Payroll Period
        |--------------------------------------------------------------------------
        |
        | Convert the selected dates into Carbon instances.
        | We use the full day (00:00:00 → 23:59:59) so no attendance
        | records are missed.
        |
        */

        $startDate = Carbon::parse($validated['start_date'])
            ->startOfDay();

        $endDate = Carbon::parse($validated['end_date'])
            ->endOfDay();


        /*
        |--------------------------------------------------------------------------
        | Retrieve Completed Attendances
        |--------------------------------------------------------------------------
        |
        | Payroll is generated only from completed attendance records.
        | Load all relationships required for payroll calculation to
        | avoid N+1 query issues.
        |
        */

        $attendances = Attendance::with([

                'employee.user',

                'shift',

            ])

            ->whereBetween('check_out_time', [

                $startDate,

                $endDate,

            ])

            ->whereIn('status', [

                'Completed',

                'Checked Out',

            ])

            ->orderBy('employee_id')

            ->orderBy('check_out_time')

            ->get();


        /*
        |--------------------------------------------------------------------------
        | Ensure There Are Records To Process
        |--------------------------------------------------------------------------
        |
        | Stop payroll generation if no completed attendance records
        | were found for the selected payroll period.
        |
        */

        if ($attendances->isEmpty()) {

            return response()->json([

                'success' => false,

                'message' => 'No completed attendance records were found for the selected payroll period.',

            ], 422);

        }


        /*
        |--------------------------------------------------------------------------
        | Initialize Counters
        |--------------------------------------------------------------------------
        |
        | These counters will be returned to the UI after payroll
        | generation has completed.
        |
        */

        $createdPayrolls = 0;

        $skippedPayrolls = 0;

        $totalPayrollAmount = 0;


        /*
        |--------------------------------------------------------------------------
        | Begin Database Transaction
        |--------------------------------------------------------------------------
        |
        | Wrap the payroll generation process inside a database
        | transaction so that either all payroll records are created
        | successfully or none are.
        |
        */

        try {

            DB::transaction(function () use (

                $attendances,
                $startDate,
                $endDate,
                &$createdPayrolls,
                &$skippedPayrolls,
                &$totalPayrollAmount

            ) {

                /*
                |--------------------------------------------------------------------------
                | Group Attendances By Employee
                |--------------------------------------------------------------------------
                |
                | Each employee should have one payroll record containing
                | multiple payroll items (one item per attendance).
                |
                */

                foreach ($attendances->groupBy('employee_id') as $employeeId => $employeeAttendances) {

                    /*
                    |--------------------------------------------------------------------------
                    | Check Existing Payroll
                    |--------------------------------------------------------------------------
                    |
                    | An employee can only have one payroll record for a payroll
                    | period. If one already exists, skip this employee.
                    |
                    */

                    $existingPayroll = Payroll::where('employee_id', $employeeId)

                        ->whereDate('period_start', $startDate)

                        ->whereDate('period_end', $endDate)

                        ->first();

                    if ($existingPayroll) {

                        $skippedPayrolls++;

                        continue;

                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Get Employee Information
                    |--------------------------------------------------------------------------
                    |
                    | Retrieve the employee from the first attendance record.
                    | All attendance records in this group belong to the same employee.
                    |
                    */

                    $employee = $employeeAttendances->first()->employee;

                    if (!$employee) {

                        continue;

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Initialize Employee Payroll Totals
                    |--------------------------------------------------------------------------
                    |
                    | These variables accumulate the employee's payroll while
                    | processing each completed attendance record.
                    |
                    */

                    $totalShifts = 0;

                    $totalHours = 0;

                    $grossPay = 0;

                    $allowance = 0;

                    $bonus = 0;

                    $deduction = 0;

                    $tax = 0;

                    $netPay = 0;


                    /*
                    |--------------------------------------------------------------------------
                    | Generate Payroll Number
                    |--------------------------------------------------------------------------
                    */


                    /*
                    |--------------------------------------------------------------------------
                    | Create Payroll Header
                    |--------------------------------------------------------------------------
                    */


                    /*
                    |--------------------------------------------------------------------------
                    | Process Employee Attendance Records
                    |--------------------------------------------------------------------------
                    */


                        /*
                        |--------------------------------------------------------------------------
                        | Calculate Shift Earnings
                        |--------------------------------------------------------------------------
                        */


                        /*
                        |--------------------------------------------------------------------------
                        | Create Payroll Item
                        |--------------------------------------------------------------------------
                        */


                        /*
                        |--------------------------------------------------------------------------
                        | Update Running Totals
                        |--------------------------------------------------------------------------
                        */


                    /*
                    |--------------------------------------------------------------------------
                    | Update Payroll Totals
                    |--------------------------------------------------------------------------
                    */


                    /*
                    |--------------------------------------------------------------------------
                    | Increment Generated Counter
                    |--------------------------------------------------------------------------
                    */

                }

            });

        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | Handle Exceptions
            |--------------------------------------------------------------------------
            */

            report($e);

            return response()->json([

                'success' => false,

                'message' => 'Payroll generation failed.',

            ], 500);

        }


            /*
            |--------------------------------------------------------------------------
            | Group Attendances By Employee
            |--------------------------------------------------------------------------
            */


                /*
                |--------------------------------------------------------------------------
                | Check Existing Payroll
                |--------------------------------------------------------------------------
                */


                /*
                |--------------------------------------------------------------------------
                | Get Employee Information
                |--------------------------------------------------------------------------
                */


                /*
                |--------------------------------------------------------------------------
                | Initialize Employee Payroll Totals
                |--------------------------------------------------------------------------
                */


                /*
                |--------------------------------------------------------------------------
                | Generate Payroll Number
                |--------------------------------------------------------------------------
                */


                /*
                |--------------------------------------------------------------------------
                | Create Payroll Header
                |--------------------------------------------------------------------------
                */


                /*
                |--------------------------------------------------------------------------
                | Process Employee Attendance Records
                |--------------------------------------------------------------------------
                */


                    /*
                    |--------------------------------------------------------------------------
                    | Calculate Shift Earnings
                    |--------------------------------------------------------------------------
                    */


                    /*
                    |--------------------------------------------------------------------------
                    | Create Payroll Item
                    |--------------------------------------------------------------------------
                    */


                    /*
                    |--------------------------------------------------------------------------
                    | Update Running Totals
                    |--------------------------------------------------------------------------
                    */


                /*
                |--------------------------------------------------------------------------
                | Update Payroll Totals
                |--------------------------------------------------------------------------
                */


                /*
                |--------------------------------------------------------------------------
                | Increment Generated Counter
                |--------------------------------------------------------------------------
                */


        /*
        |--------------------------------------------------------------------------
        | Commit Transaction
        |--------------------------------------------------------------------------
        */


        /*
        |--------------------------------------------------------------------------
        | Record Activity Log
        |--------------------------------------------------------------------------
        */


        /*
        |--------------------------------------------------------------------------
        | Return Success Response
        |--------------------------------------------------------------------------
        */


        /*
        |--------------------------------------------------------------------------
        | Handle Exceptions
        |--------------------------------------------------------------------------
        */
    }


}
