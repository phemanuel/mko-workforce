<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\ShiftAssignment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class StaffDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $employee = $user->employee;

        abort_if(!$employee, 403, 'Employee profile not found.');

        /*
        |--------------------------------------------------------------------------
        | Today's Shift
        |--------------------------------------------------------------------------
        */
        $todayShift = ShiftAssignment::with([
                'shift.supervisor',
                'shift',
                'attendance'
            ])
            ->where('employee_id', $employee->id)
            ->whereHas('shift', function ($query) {
                $query->whereDate('shift_date', today());
            })
            ->latest()
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Today's Attendance
        |--------------------------------------------------------------------------
        */

        $todayAttendance = optional($todayShift)->attendance;

        $todayStatus = 'Off Duty';

        if ($todayAttendance) {

            switch ($todayAttendance->status) {

                case 'Pending':
                    $todayStatus = 'Ready to Check In';
                    break;

                case 'Checked In':
                    $todayStatus = 'Currently Working';
                    break;

                case 'Checked Out':
                    $todayStatus = 'Shift Completed';
                    break;

                case 'Late':
                    $todayStatus = 'Late Check In';
                    break;

                case 'Absent':
                    $todayStatus = 'Absent';
                    break;

                case 'Early Leave':
                    $todayStatus = 'Left Early';
                    break;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Next Upcoming Shift
        |--------------------------------------------------------------------------
        */
        $nextShift = ShiftAssignment::with([
                'shift.supervisor',
                'shift'
            ])
            ->where('employee_id', $employee->id)
            ->whereHas('shift', function ($query) {
                $query->whereDate('shift_date', '>', today());
            })
            ->join('shifts', 'shift_assignments.shift_id', '=', 'shifts.id')
            ->orderBy('shifts.shift_date')
            ->select('shift_assignments.*')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Dashboard Statistics
        |--------------------------------------------------------------------------
        */
        $assignedShifts = ShiftAssignment::where('employee_id', $employee->id)
            ->whereIn('status', ['Assigned', 'Accepted'])
            ->count();

        $completedShifts = ShiftAssignment::where('employee_id', $employee->id)
            ->where('status', 'Completed')
            ->count();

        $upcomingShifts = ShiftAssignment::where('employee_id', $employee->id)
        ->whereHas('shift', function ($q) {
            $q->whereDate('shift_date', '>=', today());
        })
        ->whereIn('status', ['Assigned', 'Accepted'])
        ->count();

        $totalAttendance = Attendance::where('employee_id', $employee->id)->count();

        $completedAttendance = Attendance::where('employee_id', $employee->id)
            ->where('status', 'Checked Out')
            ->count();

        $attendanceRate = $totalAttendance
            ? round(($completedAttendance / $totalAttendance) * 100)
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Total Worked Hours
        |--------------------------------------------------------------------------
        */
        $workedHours = Attendance::where('employee_id', $employee->id)
            ->whereNotNull('check_in_time')
            ->whereNotNull('check_out_time')
            ->get()
            ->sum(function ($attendance) {
                return Carbon::parse($attendance->check_in_time)
                    ->diffInMinutes($attendance->check_out_time) / 60;
            });

        /*
        |--------------------------------------------------------------------------
        | Recent Attendance
        |--------------------------------------------------------------------------
        */
        $recentAttendance = Attendance::with('shift')
            ->where('employee_id', $employee->id)
            ->latest()
            ->take(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Recent Shifts
        |--------------------------------------------------------------------------
        */
        $recentShifts = ShiftAssignment::with([
                'shift.supervisor',
                'shift'
            ])
            ->where('employee_id', $employee->id)
            ->latest()
            ->take(5)
            ->get();

        return view('staff.dashboard.index', compact(
            'employee',
            'todayShift',
            'todayAttendance',
            'todayStatus',
            'nextShift',
            'assignedShifts',
            'completedShifts',
            'upcomingShifts',
            'workedHours',
            'recentAttendance',
            'recentShifts',
            'attendanceRate'
        ));
    }
}
