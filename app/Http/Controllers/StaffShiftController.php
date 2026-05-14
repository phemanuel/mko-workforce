<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ShiftAssignment;
use Illuminate\Http\Request;

class StaffShiftController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | MY SHIFTS
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $employee = auth()->user()->employee;

        /*
        |--------------------------------------------------------------------------
        | FILTERS
        |--------------------------------------------------------------------------
        */
        $status = $request->status;
        $search = $request->search;

        $query = ShiftAssignment::with('shift')
            ->where('employee_id', $employee->id);

        /*
        |--------------------------------------------------------------------------
        | STATUS FILTER
        |--------------------------------------------------------------------------
        */
        if ($status && $status != 'All') {
            $query->where('status', $status);
        }

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */
        if ($search) {

            $query->whereHas('shift', function ($q) use ($search) {

                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhere('shift_date', 'like', "%{$search}%");

            });
        }

        /*
        |--------------------------------------------------------------------------
        | RESULTS
        |--------------------------------------------------------------------------
        */
        $assignments = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | STATS
        |--------------------------------------------------------------------------
        */
        $assignedCount = ShiftAssignment::where('employee_id', $employee->id)
            ->where('status', 'Assigned')
            ->count();

        $acceptedCount = ShiftAssignment::where('employee_id', $employee->id)
            ->where('status', 'Accepted')
            ->count();

        $declinedCount = ShiftAssignment::where('employee_id', $employee->id)
            ->where('status', 'Declined')
            ->count();

        $completedCount = ShiftAssignment::where('employee_id', $employee->id)
            ->where('status', 'Completed')
            ->count();

        return view('staff.shifts.index', compact(
            'assignments',
            'assignedCount',
            'acceptedCount',
            'declinedCount',
            'completedCount',
            'status',
            'search'
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