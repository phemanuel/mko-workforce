<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Shift;

class AttendanceController extends Controller
{
    public function index()
    {
        $attendances = Attendance::with([
            'employee.user',
            'shift',
            'shift.supervisor'
        ])
        ->latest()
        ->paginate(20);

        return view('admin.attendance.index', [

            'attendances' => $attendances,

            'pendingCount' => Attendance::where('status','Pending')->count(),

            'checkedInCount' => Attendance::where('status','Checked In')->count(),

            'completedCount' => Attendance::where('status','Completed')->count(),

            'lateCount' => Attendance::where('status','Late')->count(),

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | CHECK IN
    |--------------------------------------------------------------------------
    */
    public function checkIn(Request $request, $shiftId)
    {
        $shift = Shift::findOrFail($shiftId);

        $attendance = Attendance::firstOrCreate([
            'shift_id' => $shiftId,
            'user_id' => auth()->id(),
        ]);

        $attendance->update([
            'check_in_time' => now(),

            // MOCK GPS (later replace with real navigator.geolocation)
            'check_in_lat' => $request->lat ?? 0,
            'check_in_lng' => $request->lng ?? 0,
        ]);

        /*
        |--------------------------------------------------------------------------
        | LATE LOGIC
        |--------------------------------------------------------------------------
        */
        $shiftStart = strtotime($shift->shift_date . ' ' . $shift->start_time);
        $now = now()->timestamp;

        if ($now > $shiftStart) {
            $attendance->status = 'late';
        }

        $attendance->save();

        return response()->json([
            'success' => true,
            'message' => 'Checked in successfully'
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | CHECK OUT
    |--------------------------------------------------------------------------
    */
    public function checkOut(Request $request, $shiftId)
    {
        $attendance = Attendance::where('shift_id', $shiftId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $attendance->update([
            'check_out_time' => now(),

            'check_out_lat' => $request->lat ?? 0,
            'check_out_lng' => $request->lng ?? 0,
        ]);

        /*
        |--------------------------------------------------------------------------
        | EARLY LEAVE LOGIC
        |--------------------------------------------------------------------------
        */
        $shift = Shift::findOrFail($shiftId);

        $shiftEnd = strtotime($shift->shift_date . ' ' . $shift->end_time);
        $now = now()->timestamp;

        if ($now < $shiftEnd) {
            $attendance->status = 'early_leave';
        } else {
            $attendance->status = 'completed';
        }

        $attendance->save();

        return response()->json([
            'success' => true,
            'message' => 'Checked out successfully'
        ]);
    }

    public function show(Attendance $attendance)
    {
        $attendance->load([
            'employee.user',
            'shift.supervisor'
        ]);

        return response()->json([
            'attendance' => $attendance,
            'hours_worked' => $attendance->check_in_time && $attendance->check_out_time
                ? \Carbon\Carbon::parse($attendance->check_in_time)
                    ->diff(\Carbon\Carbon::parse($attendance->check_out_time))
                    ->format('%hh %Im')
                : '--'
        ]);
    }
    
}
