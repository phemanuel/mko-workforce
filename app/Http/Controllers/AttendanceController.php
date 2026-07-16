<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Shift;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            'shift',
            'shift.supervisor',
            'resolver',
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

    public function adjustAttendance(Request $request, Attendance $attendance)
    {
        $request->validate([
            'check_in_time'  => ['required', 'date'],
            'check_out_time' => ['nullable', 'date', 'after:check_in_time'],
            'reason'         => ['required', 'string', 'max:1000'],
            'check_out_lat' => ['nullable','numeric'],
            'check_out_lng' => ['nullable','numeric'],
        ]);

        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | Keep Original Values for Activity Log
            |--------------------------------------------------------------------------
            */

            $originalCheckIn  = $attendance->check_in_time;
            $originalCheckOut = $attendance->check_out_time;
            $originalStatus   = $attendance->status;

            /*
            |--------------------------------------------------------------------------
            | Convert Request Values
            |--------------------------------------------------------------------------
            */

            $checkIn = Carbon::parse($request->check_in_time);

            $checkOut = $request->filled('check_out_time')
                ? Carbon::parse($request->check_out_time)
                : null;

            /*
            |--------------------------------------------------------------------------
            | Update Attendance
            |--------------------------------------------------------------------------
            */

            $attendance->update([
                'check_in_time'  => $checkIn,
                'check_out_time' => $checkOut,
                'remarks'        => $request->reason,

                'resolved_by'    => auth()->id(),
                'resolved_at'    => now(),

                'check_out_lat' => $request->check_out_lat,
                'check_out_lng' => $request->check_out_lng,

                'status'         => 'Checked Out',
            ]);

            /*
            |--------------------------------------------------------------------------
            | Recalculate Attendance
            |--------------------------------------------------------------------------
            */

            $this->recalculateAttendance($attendance);

            // Reload attendance so the newly calculated values are available
            $attendance->refresh();

            /*
            |--------------------------------------------------------------------------
            | Activity Log
            |--------------------------------------------------------------------------
            */

            \Log::info('Attendance Relationship Debug', [

            'attendance_id' => $attendance->id,

            'employee_loaded' => $attendance->relationLoaded('employee'),
            'employee_exists' => !is_null($attendance->employee),

            'user_loaded' => $attendance->employee
                ? $attendance->employee->relationLoaded('user')
                : false,

            'user_exists' => !is_null($attendance->employee?->user),

            'shift_loaded' => $attendance->relationLoaded('shift'),
            'shift_exists' => !is_null($attendance->shift),

        ]);

            log_activity(

                'attendance_adjusted',

                'Attendance Adjusted',

                'Attendance for employee "' .
                $attendance->employee->user->name .
                '" was adjusted.<br><br>' .

                '<strong>Shift:</strong> ' . $attendance->shift->title . '<br>' .

                '<strong>Original Check In:</strong> ' .
                ($originalCheckIn
                    ? $originalCheckIn->format('d M Y h:i:s A')
                    : 'N/A') . '<br>' .

                '<strong>New Check In:</strong> ' .
                ($attendance->check_in_time
                    ? $attendance->check_in_time->format('d M Y h:i:s A')
                    : 'N/A') . '<br><br>' .

                '<strong>Original Check Out:</strong> ' .
                ($originalCheckOut
                    ? $originalCheckOut->format('d M Y h:i:s A')
                    : 'Not Checked Out') . '<br>' .

                '<strong>New Check Out:</strong> ' .
                ($attendance->check_out_time
                    ? $attendance->check_out_time->format('d M Y h:i:s A')
                    : 'Not Checked Out') . '<br><br>' .

                '<strong>Status:</strong> ' .
                $originalStatus . ' → ' . $attendance->status . '<br>' .

                '<strong>Worked Hours:</strong> ' .
                ($attendance->worked_hours ?? 0) . ' hrs<br>' .

                '<strong>Reason:</strong> ' .
                e($request->reason)

            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Attendance updated successfully.',
                'attendance' => $attendance
            ]);

        } catch (\Throwable $e) {

            DB::rollBack();

            \Log::error('Attendance Adjustment Error', [
                'attendance_id' => $attendance->id,
                'message'       => $e->getMessage(),
                'trace'         => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to update attendance.',
            ], 500);

        }
    }

    
    // |--------------------------------------------------------------------------
    // | Recalculate Attendance
    // |--------------------------------------------------------------------------
    
    private function recalculateAttendance(Attendance $attendance): void
    {

        $shift = $attendance->shift;

        $checkIn = $attendance->check_in_time;

        $checkOut = $attendance->check_out_time;

        if (!$checkIn) {

            return;

        }

        /*
        |--------------------------------------------------------------------------
        | Shift Start & End
        |--------------------------------------------------------------------------
        */

        $shiftStart = Carbon::parse(
            $shift->shift_date . ' ' . $shift->start_time,
            $shift->timezone
        );

        $shiftEnd = Carbon::parse(
            $shift->shift_date . ' ' . $shift->end_time,
            $shift->timezone
        );

        /*
        |--------------------------------------------------------------------------
        | Late Minutes
        |--------------------------------------------------------------------------
        */

        $graceTime = $shiftStart
            ->copy()
            ->addMinutes($shift->late_after_minutes);

        $lateMinutes = 0;

        if ($checkIn->gt($graceTime)) {

            $lateMinutes = $checkIn->diffInMinutes($graceTime);

        }

        /*
        |--------------------------------------------------------------------------
        | Worked Time
        |--------------------------------------------------------------------------
        */

        $workedMinutes = 0;

        $workedHours = 0;

        $earlyLeave = 0;

        if ($checkOut) {

            $workedMinutes = $checkIn->diffInMinutes($checkOut);

            $workedHours = round($workedMinutes / 60, 2);

            if ($checkOut->lt($shiftEnd)) {

                $earlyLeave = $checkOut->diffInMinutes($shiftEnd);

            }

        }

        /*
        |--------------------------------------------------------------------------
        | Attendance Status
        |--------------------------------------------------------------------------
        */

        $status = 'Pending';

        if ($checkIn && !$checkOut) {

            $status = $lateMinutes > 0
                ? 'Late'
                : 'Checked In';

        }

        if ($checkIn && $checkOut) {

            $status = $earlyLeave > 0
                ? 'Early Leave'
                : 'Checked Out';

        }

        /*
        |--------------------------------------------------------------------------
        | Save
        |--------------------------------------------------------------------------
        */

        $attendance->update([

            'worked_minutes' => $workedMinutes,

            'worked_hours' => $workedHours,

            'late_minutes' => $lateMinutes,

            'early_leave_minutes' => $earlyLeave,

            'status' => $status,

        ]);

    }
    
}
