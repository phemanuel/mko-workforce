<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    // LIST ALL EMPLOYEES (ADMIN PANEL)
    public function index(Request $request)
    {
        $query = Employee::with(['user',
            'documents',
            'profile',
            'payroll',
            'roleDetail',
            'workEligibility',
            'skills'])

            // ONLY APPROVED EMPLOYEES
            ->whereHas('user', function ($q) {
                $q->where('approval_status', 'approved');
            });

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $query->whereHas('user', function ($q) use ($request) {

                $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%');

            });

        }

        /*
        |--------------------------------------------------------------------------
        | PAGINATION
        |--------------------------------------------------------------------------
        */

        $employees = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | ACTIVITY LOG
        |--------------------------------------------------------------------------
        */
        // dd($employees->first()->profile->getAttributes());
        log_activity(
            'view_employees',
            'Viewed employee list',
            $request->filled('search')
                ? 'Admin searched employees: ' . $request->search
                : 'Admin viewed employees'
        );

        return view('admin.employees.index', compact('employees'));
    }

    public function show(Employee $employee)
    {
        $employee->load([
            'user',
            'documents',
            'skills',
            'roleDetail',
            'profile', // ✅ ADD THIS
            'payroll',
            'workEligibility'
        ]);
         

        log_activity(
            'view_employee_profile',
            'Employee profile viewed',
            $employee->user->name . ' profile opened by ' . auth()->user()->name
        );       

        return response()->json($employee);
    }

     public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string',
            'employment_type' => 'nullable|string',
            'primary_role' => 'nullable|string',
        ]);

        /*
        |--------------------------------------------------------------------------
        | UPDATE USER
        |--------------------------------------------------------------------------
        */

        $employee->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        /*
        |--------------------------------------------------------------------------
        | UPDATE EMPLOYEE
        |--------------------------------------------------------------------------
        */

        $employee->update([
            'phone' => $request->phone,
            'employment_type' => $request->employment_type,
            'primary_role' => $request->primary_role,
        ]);

        /*
        |--------------------------------------------------------------------------
        | ACTIVITY LOG
        |--------------------------------------------------------------------------
        */

        log_activity(
            'employee_updated',
            'Employee profile updated',
            'Updated profile for '.$employee->user->name . " by " . auth()->user()->name
        );

        return response()->json([
            'success' => true,
            'message' => 'Employee updated successfully.'
        ]);
    }

    public function exportCsv()
    {
        log_activity(
            'export_employees',
            'Exported employee data',
            auth()->user()->name . ' exported employee list to CSV'
        );

        $employees = Employee::with('user')->get();

        $filename = "employees.csv";
        $handle = fopen($filename, 'w+');

        fputcsv($handle, ['Name', 'Email', 'Role', 'Status']);

        foreach ($employees as $e) {
            fputcsv($handle, [
                $e->user->name,
                $e->user->email,
                $e->primary_role,
                $e->user->status
            ]);
        }

        fclose($handle);

        return response()->download($filename)->deleteFileAfterSend(true);
    }


}