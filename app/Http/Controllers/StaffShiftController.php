<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\ShiftAssignment;
use Carbon\Carbon;
use App\Models\Shift;
use Illuminate\Support\Facades\DB;

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
        | AVAILABLE SHIFTS MATCHING EMPLOYEE ROLE
        |--------------------------------------------------------------------------
        */

        $availableShifts = collect();

        $employeeRole = $employee->roleDetail?->role_type;

        if ($employeeRole) {
            $availableShifts = Shift::with('supervisor')
                ->where('role_required', $employeeRole)
                ->whereIn('status', ['Open', 'Assigned'])
                ->whereDate('shift_date', '>=', today())

                // Exclude shifts that are already full.
                ->whereRaw("
                    (
                        SELECT COUNT(*)
                        FROM shift_assignments
                        WHERE shift_assignments.shift_id = shifts.id
                        AND shift_assignments.status IN ('Assigned', 'Accepted')
                    ) < shifts.required_staff
                ")

                // Prevent duplicate active or completed assignments.
                ->whereDoesntHave('assignments', function ($query) use ($employee) {
                    $query->where('employee_id', $employee->id)
                        ->whereIn('status', ['Assigned', 'Accepted', 'Completed']);
                })

                // Display the current number of occupied positions.
                ->withCount([
                    'assignments as occupied_positions' => function ($query) {
                        $query->whereIn('status', ['Assigned', 'Accepted']);
                    }
                ])

                ->orderBy('shift_date')
                ->orderBy('start_time')
                ->get();
        }

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
            ->where('status', 'Assigned')
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
            'attendanceRate',
            'availableShifts'
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

    /*
    |--------------------------------------------------------------------------
    | ACCEPT AVAILABLE SHIFT
    |--------------------------------------------------------------------------
    */

    public function acceptAvailable($id)
    {
        $user = Auth::user();

        $employee = $user->employee;

        abort_if(!$employee, 403, 'Employee profile not found.');

        try {

            $result = DB::transaction(function () use ($id, $employee) {

                /*
                |--------------------------------------------------------------------------
                | LOCK SHIFT
                |--------------------------------------------------------------------------
                */

                $shift = Shift::where('id', $id)
                    ->lockForUpdate()
                    ->firstOrFail();

                /*
                |--------------------------------------------------------------------------
                | VERIFY EMPLOYEE ROLE
                |--------------------------------------------------------------------------
                */

                $employeeRole = $employee->roleDetail?->role_type;

                if (
                    !$employeeRole ||
                    $employeeRole !== $shift->role_required
                ) {

                    return [
                        'success' => false,
                        'message' => 'This shift is not available for your role.',
                        'status' => 403
                    ];

                }

                /*
                |--------------------------------------------------------------------------
                | VERIFY SHIFT STATUS
                |--------------------------------------------------------------------------
                */

                if (!in_array($shift->status, ['Open', 'Assigned'])) {

                    return [
                        'success' => false,
                        'message' => "This shift is {$shift->status} and cannot be accepted.",
                        'status' => 422
                    ];

                }

                /*
                |--------------------------------------------------------------------------
                | VERIFY SHIFT DATE
                |--------------------------------------------------------------------------
                */

                if (Carbon::parse($shift->shift_date)->startOfDay()->lt(today())) {

                    return [
                        'success' => false,
                        'message' => 'This shift has already passed.',
                        'status' => 422
                    ];

                }

                /*
                |--------------------------------------------------------------------------
                | PREVENT ACCEPTING A LOCKED SHIFT
                |--------------------------------------------------------------------------
                */

                if ($shift->isLocked()) {

                    return [
                        'success' => false,
                        'message' => 'This shift has already started and can no longer be accepted.',
                        'status' => 422
                    ];

                }

                /*
                |--------------------------------------------------------------------------
                | CHECK EXISTING ASSIGNMENT
                |--------------------------------------------------------------------------
                */

                $assignment = ShiftAssignment::where('shift_id', $shift->id)
                    ->where('employee_id', $employee->id)
                    ->lockForUpdate()
                    ->first();

                if ($assignment && $assignment->status !== 'Declined') {

                    return [
                        'success' => false,
                        'message' => 'You already have an assignment for this shift.',
                        'status' => 422
                    ];

                }

                /*
                |--------------------------------------------------------------------------
                | CHECK AVAILABLE CAPACITY
                |--------------------------------------------------------------------------
                */

                $occupiedPositions = ShiftAssignment::where('shift_id', $shift->id)
                    ->whereIn('status', ['Assigned', 'Accepted'])
                    ->count();

                if ($occupiedPositions >= $shift->required_staff) {

                    return [
                        'success' => false,
                        'message' => 'Sorry, this shift is already full. Please check other available shifts.',
                        'status' => 422
                    ];

                }

                /*
                |--------------------------------------------------------------------------
                | CREATE OR REACTIVATE ASSIGNMENT
                |--------------------------------------------------------------------------
                */

                if ($assignment) {

                    $assignment->update([
                        'status' => 'Accepted'
                    ]);

                } else {

                    $assignment = ShiftAssignment::create([
                        'shift_id' => $shift->id,
                        'employee_id' => $employee->id,
                        'status' => 'Accepted'
                    ]);

                }

                /*
                |--------------------------------------------------------------------------
                | CREATE OR RESTORE ATTENDANCE RECORD
                |--------------------------------------------------------------------------
                */

                Attendance::firstOrCreate(
                    [
                        'shift_assignment_id' => $assignment->id
                    ],
                    [
                        'shift_id' => $shift->id,
                        'employee_id' => $employee->id,
                        'status' => 'Pending'
                    ]
                );

                /*
                |--------------------------------------------------------------------------
                | UPDATE SHIFT STATUS
                |--------------------------------------------------------------------------
                */

                $activeAssignments = ShiftAssignment::where('shift_id', $shift->id)
                    ->whereIn('status', ['Assigned', 'Accepted'])
                    ->count();

                $shift->update([
                    'status' => $activeAssignments > 0 ? 'Assigned' : 'Open'
                ]);

                return [
                    'success' => true,
                    'message' => 'Shift accepted successfully, check in from your dashboards(Today shifts).',
                    'status' => 200
                ];

            });

            return response()->json([
                'success' => $result['success'],
                'message' => $result['message']
            ], $result['status']);

        } catch (\Throwable $e) {

            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Unable to accept this shift at the moment. Please try again.'
            ], 500);

        }
    }
}