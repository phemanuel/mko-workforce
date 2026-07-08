<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Shift;
use App\Models\ShiftAssignment;
use App\Models\Employee;
use App\Models\Attendance;

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
        ->get();
        // ->paginate(6);

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
                'timezone' => 'required',
                'check_in_open_minutes' => 'required',
                'late_after_minutes' => 'required',
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
                'timezone' => $validated['timezone'],
                'check_in_open_minutes' => $validated['check_in_open_minutes'],
                'late_after_minutes' => $validated['late_after_minutes'],
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

        /*
        |--------------------------------------------------------------------------
        | Prevent Updating Locked Shift
        |--------------------------------------------------------------------------
        */

        if ($shift->isLocked()) {

            return response()->json([
                'success' => false,
                'message' => 'This shift is locked because attendance has already started. It can no longer be edited.'
            ], 422);

        }

        $validated = $request->validate([

            'title' => 'required|string',

            'shift_date' => 'required|date',

            'start_time' => 'required',

            'end_time' => 'required',

            'timezone' => 'required',

            'check_in_open_minutes' => 'required|integer|min:0',

            'late_after_minutes' => 'required|integer|min:0',

            'location' => 'nullable|string',

            'required_staff' => 'nullable|integer|min:1',

            'hourly_rate' => 'nullable|numeric|min:0',

            'status' => 'required|string',

            'instructions' => 'nullable|string',

            'notes' => 'nullable|string',

        ]);

        $shift->update($validated);

        return response()->json([

            'success' => true,

            'message' => 'Shift updated successfully.',

            'shift' => $shift

        ]);
    }
    
    public function destroy($id)
    {
        $shift = Shift::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Prevent Deleting Locked Shift
        |--------------------------------------------------------------------------
        */

        if ($shift->isLocked()) {

            return response()->json([
                'success' => false,
                'message' => 'This shift is locked because attendance has already started. It cannot be deleted.'
            ], 422);

        }

        /*
        |--------------------------------------------------------------------------
        | Prevent Deleting Shift With Assignments
        |--------------------------------------------------------------------------
        */

        if ($shift->assignments()->exists()) {

            return response()->json([
                'success' => false,
                'message' => 'Remove all assigned staff before deleting this shift.'
            ], 422);

        }

        $shift->delete();

        return response()->json([

            'success' => true,

            'message' => 'Shift deleted successfully.'

        ]);
    }
    

    public function complete($id)
    {
        $shift = Shift::findOrFail($id);

        if ($shift->status == 'Completed') {

            return response()->json([
                'success' => false,
                'message' => 'Shift has already been completed.'
            ], 422);

        }

        $shift->update([
            'status' => 'Completed'
        ]);

        return response()->json([

            'success' => true,

            'message' => 'Shift marked as completed.',

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
        $request->validate([
            'employees' => 'required|array|min:1',
            'employees.*' => 'exists:employees,id',
        ]);

        $shift = Shift::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | LOCKED SHIFT
        |--------------------------------------------------------------------------
        */

        if ($shift->isLocked()) {

            return response()->json([
                'success' => false,
                'message' => 'This shift has already started. No additional staff can be assigned.'
            ], 422);

        }

        /*
        |--------------------------------------------------------------------------
        | COMPLETED / CANCELLED SHIFT
        |--------------------------------------------------------------------------
        */

        if (in_array($shift->status, ['Completed', 'Cancelled'])) {

            return response()->json([
                'success' => false,
                'message' => "Cannot assign staff to a {$shift->status} shift."
            ], 422);

        }

        /*
        |--------------------------------------------------------------------------
        | CURRENT ACTIVE ASSIGNMENTS
        |--------------------------------------------------------------------------
        */

        $currentCount = ShiftAssignment::where('shift_id', $id)
            ->where('status', 'Assigned')
            ->count();

        $incoming = count($request->employees);

        /*
        |--------------------------------------------------------------------------
        | LIMIT CHECK
        |--------------------------------------------------------------------------
        */

        if (($currentCount + $incoming) > $shift->required_staff) {

            return response()->json([
                'success' => false,
                'message' => 'Shift staffing limit exceeded.'
            ], 422);

        }

        /*
        |--------------------------------------------------------------------------
        | ASSIGN STAFF
        |--------------------------------------------------------------------------
        */

        foreach ($request->employees as $employeeId) {

            /*
            |--------------------------------------------------------------------------
            | Prevent Duplicate Assignment
            |--------------------------------------------------------------------------
            */

            if (
                ShiftAssignment::where('shift_id', $id)
                    ->where('employee_id', $employeeId)
                    ->exists()
            ) {
                continue;
            }

            $assignment = ShiftAssignment::create([

                'shift_id'    => $id,

                'employee_id' => $employeeId,

                'status'      => 'Assigned'

            ]);

            /*
            |--------------------------------------------------------------------------
            | Attendance Record
            |--------------------------------------------------------------------------
            */

            Attendance::create([

                'shift_assignment_id' => $assignment->id,

                'shift_id'    => $shift->id,

                'employee_id' => $employeeId,

                'status'      => 'Pending'

            ]);

        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE SHIFT STATUS
        |--------------------------------------------------------------------------
        */

        $activeAssignments = ShiftAssignment::where('shift_id', $id)
            ->where('status', 'Assigned')
            ->count();

        $shift->update([

            'status' => $activeAssignments > 0
                ? 'Assigned'
                : 'Open'

        ]);

        /*
        |--------------------------------------------------------------------------
        | Activity Log
        |--------------------------------------------------------------------------
        */

        log_activity(

            'shift_assignment',

            'Shift Assignment',

            "{$incoming} staff assigned to {$shift->title}"

        );

        return response()->json([

            'success' => true,

            'message' => 'Staff assigned successfully.',

            'active_assignments' => $activeAssignments,

            'shift_status' => $shift->status

        ]);
    }

    public function unassign($assignmentId)
    {
        $activeAssignments = 0;

        DB::transaction(function () use ($assignmentId, &$activeAssignments) {

            $assignment = ShiftAssignment::with('shift')->findOrFail($assignmentId);

            $shift = $assignment->shift;

            /*
            |--------------------------------------------------------------------------
            | LOCKED SHIFT
            |--------------------------------------------------------------------------
            */

            if ($shift->isLocked()) {

                throw new \Exception(
                    'This shift has already started. Staff can no longer be removed.'
                );

            }

            /*
            |--------------------------------------------------------------------------
            | COMPLETED / CANCELLED SHIFT
            |--------------------------------------------------------------------------
            */

            if (in_array($shift->status, ['Completed', 'Cancelled'])) {

                throw new \Exception(
                    "Cannot modify a {$shift->status} shift."
                );

            }

            /*
            |--------------------------------------------------------------------------
            | DELETE ATTENDANCE
            |--------------------------------------------------------------------------
            */

            Attendance::where(
                'shift_assignment_id',
                $assignment->id
            )->delete();

            /*
            |--------------------------------------------------------------------------
            | DELETE ASSIGNMENT
            |--------------------------------------------------------------------------
            */

            $assignment->delete();

            /*
            |--------------------------------------------------------------------------
            | UPDATE SHIFT STATUS
            |--------------------------------------------------------------------------
            */

            $activeAssignments = ShiftAssignment::where(
                'shift_id',
                $shift->id
            )
            ->where('status', 'Assigned')
            ->count();

            $shift->update([

                'status' => $activeAssignments > 0
                    ? 'Assigned'
                    : 'Open'

            ]);

            /*
            |--------------------------------------------------------------------------
            | ACTIVITY LOG
            |--------------------------------------------------------------------------
            */

            log_activity(

                'shift_unassigned',

                'Shift Unassignment',

                'A staff member was removed from shift "' .
                $shift->title .
                '"'

            );

        });

        return response()->json([

            'success' => true,

            'message' => 'Staff unassigned successfully.',

            'remaining_assigned' => $activeAssignments

        ]);
    }
    
    
}
