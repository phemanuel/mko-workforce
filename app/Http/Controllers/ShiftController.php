<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shift;
use App\Models\ShiftAssignment;
use App\Models\Employee;

class ShiftController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | SHIFT LIST
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $shifts = Shift::withCount('assignments')
        // ->where('status', 'open')
        ->latest()
        ->paginate(6);

        log_activity(
            'shift_viewed',
            'Shifts viewed',
            'Shift module was viewed by ' . auth()->user()->name
        );

        return view('admin.shifts.index', compact('shifts'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /*
    |--------------------------------------------------------------------------
    | STORE SHIFT
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        try {

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'role_required' => 'required|string',
                'shift_date' => 'required|date',
                'start_time' => 'required',
                'end_time' => 'required',
                'location' => 'nullable|string|max:255',
                'required_staff' => 'nullable|integer',
                'hourly_rate' => 'nullable|numeric',
                'description' => 'nullable|string',
                'supervisor_id' => 'nullable|integer',
                'instructions' => 'nullable|string',
                'notes' => 'nullable|string',
                'attachment' => 'nullable|file|max:5120',
            ]);

            /*
            |--------------------------------------------------------------------------
            | HANDLE FILE UPLOAD
            |--------------------------------------------------------------------------
            */
            $attachmentPath = null;

            if ($request->hasFile('attachment')) {
                $attachmentPath = $request->file('attachment')
                    ->store('shifts/attachments', 'public');
            }

            /*
            |--------------------------------------------------------------------------
            | FALLBACK SUPERVISOR (ADMIN IF EMPTY)
            |--------------------------------------------------------------------------
            */
            $supervisorId = $validated['supervisor_id']
                ?? auth()->id();

            /*
            |--------------------------------------------------------------------------
            | CREATE SHIFT
            |--------------------------------------------------------------------------
            */
            $shift = Shift::create([
                'title' => $validated['title'],
                'role_required' => $validated['role_required'],
                'shift_date' => $validated['shift_date'],
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'location' => $validated['location'] ?? null,
                'required_staff' => $validated['required_staff'] ?? 1,
                'hourly_rate' => $validated['hourly_rate'] ?? 0,
                'description' => $validated['description'] ?? null,

                'supervisor_id' => $supervisorId,
                'instructions' => $validated['instructions'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'attachment' => $attachmentPath,

                'status' => 'Open',
            ]);

            log_activity(
                'shift_created',
                'Shift created',
                'New shift created by ' . auth()->user()->name
            );

            return response()->json([
                'success' => true,
                'message' => 'Shift created successfully',
                'shift' => $shift
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {

            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $shift = Shift::with([
            'assignments.employee.user',
            'supervisor'
        ])->findOrFail($id);

        return response()->json([
            'success' => true,
            'shift' => $shift
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $shift = Shift::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string',
            'shift_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'location' => 'nullable|string',
            'required_staff' => 'nullable|integer',
            'hourly_rate' => 'nullable|numeric',
            'status' => 'required|string',
            'instructions' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $shift->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Shift updated successfully',
            'shift' => $shift
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function complete($id)
    {
        $shift = Shift::findOrFail($id);

        $shift->update([
            'status' => 'Completed'
        ]);

        return response()->json([
            'success' => true,
            'shift' => $shift
        ]);
    }

    public function assignData($id)
    {
        $shift = Shift::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | ASSIGNED STAFF (ACTIVE ONLY)
        |--------------------------------------------------------------------------
        */
        $assigned = ShiftAssignment::where('shift_id', $id)
            ->where('status', 'Assigned')
            ->with('employee.user')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | GET ALREADY ASSIGNED EMPLOYEE IDS
        |--------------------------------------------------------------------------
        */
        $assignedEmployeeIds = $assigned->pluck('employee_id')->toArray();

        /*
        |--------------------------------------------------------------------------
        | LOAD ONLY AVAILABLE STAFF (EXCLUDING ASSIGNED)
        |--------------------------------------------------------------------------
        */
        $staff = Employee::where('primary_role', $shift->role_required)
            ->whereNotIn('id', $assignedEmployeeIds)
            ->with('user')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */
        return response()->json([
            'shift' => $shift,
            'assigned' => $assigned,
            'staff' => $staff
        ]);
    }

    public function assign(Request $request, $id)
    {
        $shift = Shift::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | CURRENT ACTIVE ASSIGNMENTS
        |--------------------------------------------------------------------------
        */
        $currentCount = ShiftAssignment::where('shift_id', $id)
            ->where('status', 'Assigned')
            ->count();

        $incoming = count($request->employees ?? []);

        /*
        |--------------------------------------------------------------------------
        | LIMIT CHECK
        |--------------------------------------------------------------------------
        */
        if (($currentCount + $incoming) > $shift->required_staff) {

            return response()->json([
                'message' => 'Shift staffing limit exceeded'
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | ASSIGN STAFF
        |--------------------------------------------------------------------------
        */
        foreach ($request->employees as $employeeId) {

            $assignment = ShiftAssignment::firstOrCreate(
                [
                    'shift_id' => $id,
                    'employee_id' => $employeeId,
                ],
                [
                    'status' => 'Assigned'
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | REASSIGN IF PREVIOUSLY UNASSIGNED
            |--------------------------------------------------------------------------
            */
            if ($assignment->status !== 'Assigned') {

                $assignment->update([
                    'status' => 'Assigned'
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE SHIFT STATUS
        |--------------------------------------------------------------------------
        */
        $activeAssignments = ShiftAssignment::where('shift_id', $id)
            ->where('status', 'Assigned')
            ->count();

        if ($activeAssignments > 0) {

            $shift->update([
                'status' => 'Assigned'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */
        return response()->json([
            'message' => 'Staff assigned successfully',
            'active_assignments' => $activeAssignments,
            'shift_status' => $shift->status
        ]);
    }

    public function unassign($assignmentId)
    {
        $assignment = ShiftAssignment::findOrFail($assignmentId);

        $shiftId = $assignment->shift_id;

        // remove assignment completely
        $assignment->delete();

        /*
        |--------------------------------------------------------------------------
        | UPDATE SHIFT STATUS BASED ON REMAINING ASSIGNMENTS
        |--------------------------------------------------------------------------
        */
        $activeAssignments = ShiftAssignment::where('shift_id', $shiftId)
            ->where('status', 'Assigned')
            ->count();

        $shift = Shift::findOrFail($shiftId);

        $shift->update([
            'status' => $activeAssignments > 0 ? 'Assigned' : 'Open'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Staff unassigned successfully',
            'remaining_assigned' => $activeAssignments
        ]);
    }
    
    public function adminCheckIn(Request $request, $shiftId, $userId)
    {
        $shift = Shift::findOrFail($shiftId);

        $attendance = Attendance::firstOrCreate([
            'shift_id' => $shiftId,
            'user_id' => $userId,
        ]);

        $attendance->update([
            'check_in_time' => now(),

            // MOCK GPS
            'check_in_lat' => $request->lat ?? 0,
            'check_in_lng' => $request->lng ?? 0,
        ]);

        /*
        |--------------------------------------------------------------------------
        | LATE LOGIC
        |--------------------------------------------------------------------------
        */
        $shiftStart = strtotime($shift->shift_date . ' ' . $shift->start_time);

        if (now()->timestamp > $shiftStart) {
            $attendance->status = 'late';
        } else {
            $attendance->status = 'present';
        }

        $attendance->save();

        return response()->json([
            'success' => true,
            'message' => 'Staff checked in successfully (admin)'
        ]);
    }

    public function adminCheckOut(Request $request, $shiftId, $userId)
    {
        $attendance = Attendance::where('shift_id', $shiftId)
            ->where('user_id', $userId)
            ->firstOrFail();

        $attendance->update([
            'check_out_time' => now(),

            // MOCK GPS
            'check_out_lat' => $request->lat ?? 0,
            'check_out_lng' => $request->lng ?? 0,
        ]);

        $shift = Shift::findOrFail($shiftId);

        $shiftEnd = strtotime($shift->shift_date . ' ' . $shift->end_time);

        if (now()->timestamp < $shiftEnd) {
            $attendance->status = 'early_leave';
        } else {
            $attendance->status = 'completed';
        }

        $attendance->save();

        return response()->json([
            'success' => true,
            'message' => 'Staff checked out successfully (admin)'
        ]);
    }
}
