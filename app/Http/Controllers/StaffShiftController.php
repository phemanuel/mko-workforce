<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\ShiftAssignment;
use Carbon\Carbon;

class StaffShiftController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | MY SHIFTS
    |--------------------------------------------------------------------------
    */
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
        $attendanceAction = null;

        if ($todayAttendance) {

            // Employee has not checked in yet
            if (
                is_null($todayAttendance->check_in_time) &&
                is_null($todayAttendance->check_out_time)
            ) {

                $attendanceAction = 'checkin';

                $todayStatus = match ($todayAttendance->status) {

                    'Late'    => 'Late Check In',
                    'Absent'  => 'Absent',
                    default   => 'Ready to Check In',

                };

            }

            // Employee has checked in but not checked out
            elseif (
                !is_null($todayAttendance->check_in_time) &&
                is_null($todayAttendance->check_out_time)
            ) {

                $attendanceAction = 'checkout';

                $todayStatus = 'Currently Working';

            }

            // Employee has completed the shift
            elseif (
                !is_null($todayAttendance->check_in_time) &&
                !is_null($todayAttendance->check_out_time)
            ) {

                $attendanceAction = 'completed';

                $todayStatus = match ($todayAttendance->status) {

                    'Early Leave' => 'Left Early',
                    default       => 'Shift Completed',

                };

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

        $pendingShifts = ShiftAssignment::where('employee_id', $employee->id)
            ->where('status', 'Pending')
            ->count();

        $upcomingShifts = ShiftAssignment::where('employee_id', $employee->id)
        ->whereHas('shift', function ($q) {
            $q->whereDate('shift_date', '>=', today());
        })
        ->whereIn('status', ['Assigned', 'Accepted'])
        ->count();

        $todayShifts = ShiftAssignment::where('employee_id', $employee->id)
        ->whereHas('shift', function ($q) {
            $q->whereDate('shift_date', '=', today());
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
            ->paginate(5);

        return view('staff.shifts.index', compact(
            'employee',
            'todayShift',
            'todayAttendance',
            'todayStatus',
            'attendanceAction',
            'nextShift',
            'assignedShifts',
            'completedShifts',
            'upcomingShifts',
            'todayShifts',
            'workedHours',
            'recentAttendance',
            'recentShifts',
            'pendingShifts',
            'attendanceRate'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | ACCEPT SHIFT
    |--------------------------------------------------------------------------
    */
    public function accept($id)
    {
        $assignment = ShiftAssignment::where('employee_id', auth()->user()->employee->id)
            ->findOrFail($id);

        $assignment->update([
            'status' => 'Accepted'
        ]);

        return back()->with('success', 'Shift accepted successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | DECLINE SHIFT
    |--------------------------------------------------------------------------
    */
    public function decline($id)
    {
        $assignment = ShiftAssignment::where('employee_id', auth()->user()->employee->id)
            ->findOrFail($id);

        $assignment->update([
            'status' => 'Declined'
        ]);

        return back()->with('success', 'Shift declined successfully.');
    }
}