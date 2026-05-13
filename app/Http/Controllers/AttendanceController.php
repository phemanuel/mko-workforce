<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Shift;

class AttendanceController extends Controller
{
    public function index()
    {
        $shifts = Shift::with([
            'assignments.employee.user',
            'attendances'
        ])
        ->where('status', 'Assigned')
        ->latest()
        ->get();

        return view('admin.attendance.index', compact('shifts'));
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
}
