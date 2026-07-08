<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Shift;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index()
    {
        $attendances = Attendance::with([
                'employee.user',
                'employee.roleDetail',
                'shift.supervisor',
                'shift'
            ])
            ->latest('created_at')
            ->get()
            ->groupBy('employee_id');

        return view('admin.attendance.index', [

            'attendances' => $attendances,

            'pendingCount' => Attendance::where('status','Pending')->count(),

            'checkedInCount' => Attendance::where('status','Checked In')->count(),

            'completedCount' => Attendance::whereIn('status',[
                'Checked Out',
                'Completed'
            ])->count(),

            'lateCount' => Attendance::where('status','Late')->count(),

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | CHECK IN
    |--------------------------------------------------------------------------
    */
    public function checkIn(Request $request)
    {
        $request->validate([
            'attendance_id' => 'required|exists:attendances,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $attendance = Attendance::with([
            'shift',
            'employee'
        ])->findOrFail($request->attendance_id);

        /*
        |--------------------------------------------------------------------------
        | Ownership Check
        |--------------------------------------------------------------------------
        */

        if ($attendance->employee->user_id != Auth::id()) {

            return response()->json([
                'success' => false,
                'message' => 'Unauthorized attendance record.'
            ], 403);

        }

        /*
        |--------------------------------------------------------------------------
        | Already Checked In
        |--------------------------------------------------------------------------
        */

        if ($attendance->check_in_time) {

            return response()->json([
                'success' => false,
                'message' => 'You have already checked in.'
            ], 422);

        }

        $shift = $attendance->shift;

        /*
        |--------------------------------------------------------------------------
        | Timezone
        |--------------------------------------------------------------------------
        */

        $timezone = $shift->timezone ?? config('app.timezone');

        $now = Carbon::now($timezone);

        $shiftStart = Carbon::parse(
            $shift->shift_date . ' ' . $shift->start_time,
            $timezone
        );

        $shiftEnd = Carbon::parse(
            $shift->shift_date . ' ' . $shift->end_time,
            $timezone
        );

        /*
        |--------------------------------------------------------------------------
        | Check-In Window
        |--------------------------------------------------------------------------
        */

        $checkInOpen = $shiftStart
            ->copy()
            ->subMinutes($shift->check_in_open_minutes);

        if ($now->lt($checkInOpen)) {

            return response()->json([
                'success' => false,
                'message' => 'Check-in opens at '
                    . $checkInOpen->format('h:i A')
            ], 422);

        }

        /*
        |--------------------------------------------------------------------------
        | Shift Already Ended
        |--------------------------------------------------------------------------
        */

        if ($now->gt($shiftEnd)) {

            return response()->json([
                'success' => false,
                'message' => 'This shift has already ended.'
            ], 422);

        }

        /*
        |--------------------------------------------------------------------------
        | Late Calculation
        |--------------------------------------------------------------------------
        */

        $lateThreshold = $shiftStart
            ->copy()
            ->addMinutes($shift->late_after_minutes);

        $status = 'Checked In';

        $lateMinutes = 0;

        if ($now->gt($lateThreshold)) {

            $status = 'Late';

            $lateMinutes = $shiftStart->diffInMinutes($now);

        }

        /*
        |--------------------------------------------------------------------------
        | Save
        |--------------------------------------------------------------------------
        */

        $attendance->update([

            'check_in_time' => $now,

            'check_in_lat' => $request->latitude,

            'check_in_lng' => $request->longitude,

            'late_minutes' => $lateMinutes,

            'status' => $status,

        ]);

        return response()->json([

            'success' => true,

            'message' => $status == 'Late'
                ? "Checked in successfully ({$lateMinutes} mins late)."
                : 'Checked in successfully.',

            'status' => $status,

            'check_in_time' => $now->format('h:i A'),

        ]);
    }
    /*
    |--------------------------------------------------------------------------
    | CHECK OUT
    |--------------------------------------------------------------------------
    */
    public function checkOut(Request $request)
    {
        $request->validate([
            'attendance_id' => 'required|exists:attendances,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $attendance = Attendance::with([
            'shift',
            'employee',
            'assignment'
        ])->findOrFail($request->attendance_id);

        /*
        |--------------------------------------------------------------------------
        | Ownership Check
        |--------------------------------------------------------------------------
        */

        if ($attendance->employee->user_id != Auth::id()) {

            return response()->json([
                'success' => false,
                'message' => 'Unauthorized attendance record.'
            ], 403);

        }

        /*
        |--------------------------------------------------------------------------
        | Must Check In First
        |--------------------------------------------------------------------------
        */

        if (!$attendance->check_in_time) {

            return response()->json([
                'success' => false,
                'message' => 'You must check in first.'
            ], 422);

        }

        /*
        |--------------------------------------------------------------------------
        | Already Checked Out
        |--------------------------------------------------------------------------
        */

        if ($attendance->check_out_time) {

            return response()->json([
                'success' => false,
                'message' => 'You have already checked out.'
            ], 422);

        }

        $shift = $attendance->shift;

        $timezone = $shift->timezone ?? config('app.timezone');

        $now = Carbon::now($timezone);

        $shiftEnd = Carbon::parse(
            $shift->shift_date.' '.$shift->end_time,
            $timezone
        );

        /*
        |--------------------------------------------------------------------------
        | Early Leave
        |--------------------------------------------------------------------------
        */

        $earlyLeaveMinutes = 0;

        $status = 'Checked Out';

        if ($now->lt($shiftEnd)) {

            $earlyLeaveMinutes = $now->diffInMinutes($shiftEnd);

            $status = 'Early Leave';

        }

        /*
        |--------------------------------------------------------------------------
        | Save Attendance
        |--------------------------------------------------------------------------
        */
         $workedMinutes = $attendance->check_in_time
            ->diffInMinutes($attendance->check_out_time);

        $attendance->update([

            'check_out_time' => $now,
            'check_out_lat' => $request->latitude,
            'check_out_lng' => $request->longitude,
            'early_leave_minutes' => $earlyLeaveMinutes,
            'status' => $status,
            'worked_minutes' => $workedMinutes,
            'worked_hours' => round($workedMinutes / 60, 2),

        ]);        

        /*
        |--------------------------------------------------------------------------
        | Complete Assignment
        |--------------------------------------------------------------------------
        */

        if ($attendance->assignment) {

            $attendance->assignment->update([

                'status' => 'Completed'

            ]);

        }   
        

        return response()->json([

            'success' => true,

            'message' => $status == 'Early Leave'
                ? "Checked out ({$earlyLeaveMinutes} mins early)."
                : 'Checked out successfully.',

            'status' => $status,

            'check_out_time' => $now->format('h:i A'),

        ]);
    }

    public function show(Attendance $attendance)
    {
        $attendance->load([
            'employee.user',
            'shift.supervisor',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Worked Hours
        |--------------------------------------------------------------------------
        */
        $attendance->worked_hours =
            ($attendance->check_in_time && $attendance->check_out_time)
                ? Carbon::parse($attendance->check_in_time)
                    ->diff(Carbon::parse($attendance->check_out_time))
                    ->format('%hh %Im')
                : '--';

        /*
        |--------------------------------------------------------------------------
        | Flags
        |--------------------------------------------------------------------------
        */
        $attendance->late = $attendance->status === 'Late';
        $attendance->early_leave = $attendance->status === 'Early Leave';

        /*
        |--------------------------------------------------------------------------
        | Supervisor Details
        |--------------------------------------------------------------------------
        */
        if ($attendance->shift->supervisor) {

            $attendance->shift->supervisor_name =
                $attendance->shift->supervisor->name;

            $attendance->shift->supervisor_role = 'Supervisor';

        } else {

            $admin = User::where('role_id', 1)->first();

            $attendance->shift->supervisor_name =
                $admin?->name ?? 'Administrator';

            $attendance->shift->supervisor_role = 'Administrator';
        }

        return response()->json($attendance);
    }

    public function staffAttendance()
    {
        $employee = Auth::user()->employee;

        abort_if(!$employee, 403);

        $attendances = Attendance::with([
                'shift.supervisor',
                'shift'
            ])
            ->where('employee_id', $employee->id)
            ->latest()
            ->paginate(5);

        $presentCount = Attendance::where('employee_id', $employee->id)
            ->whereIn('status', ['Checked Out', 'Completed'])
            ->count();

        $lateCount = Attendance::where('employee_id', $employee->id)
            ->where('status', 'Late')
            ->count();

        $workedHours = Attendance::where('employee_id', $employee->id)
            ->whereNotNull('check_in_time')
            ->whereNotNull('check_out_time')
            ->get()
            ->sum(function ($attendance) {
                return $attendance->check_in_time
                    ->diffInMinutes($attendance->check_out_time) / 60;
            });

        $attendanceRate = $attendances->total()
            ? round(($presentCount / $attendances->total()) * 100)
            : 0;

        return view('staff.attendance.index', compact(
            'attendances',
            'presentCount',
            'lateCount',
            'workedHours',
            'attendanceRate'
        ));
    }

    public function staffAttendanceShow(Attendance $attendance)
    {
        abort_if(
            $attendance->employee_id != Auth::user()->employee->id,
            403
        );

        $attendance->load([
            'shift.supervisor',
            'shift'
        ]);

        return response()->json($attendance);
    }
    
}
