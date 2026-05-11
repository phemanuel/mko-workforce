<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $countries = [
            'Afghanistan',
            'Albania',
            'Algeria',
            'Andorra',
            'Angola',
            'Argentina',
            'Australia',
            'Austria',
            'Bangladesh',
            'Belgium',
            'Brazil',
            'Canada',
            'China',
            'Denmark',
            'Egypt',
            'France',
            'Germany',
            'Ghana',
            'India',
            'Ireland',
            'Italy',
            'Japan',
            'Kenya',
            'Mexico',
            'Netherlands',
            'Nigeria',
            'Norway',
            'Pakistan',
            'Poland',
            'Portugal',
            'Qatar',
            'Russia',
            'Saudi Arabia',
            'South Africa',
            'Spain',
            'Sweden',
            'Switzerland',
            'Turkey',
            'UAE',
            'United Kingdom',
            'United States',
            'Zimbabwe',
        ];

        sort($countries);
         

        log_activity(
            'view_employee_profile',
            'Employee profile viewed',
            $employee->user->name . ' profile opened by ' . auth()->user()->name
        );       

        return response()->json($employee);
    }

    public function edit(Employee $employee)
    {
        $employee->load([
            'user',
            'profile',
            'roleDetail',
            'workEligibility',
            'payroll',
            'documents',
            'skills'
        ]);

        log_activity(
            'edit_employee_open',
            'Opened employee edit form',
            $employee->user->name . ' edit form opened by ' . auth()->user()->name
        );

        return response()->json($employee);
    }

     public function update(Request $request, Employee $employee)
    {
        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | USER UPDATE
            |--------------------------------------------------------------------------
            */
            $employee->user()->update([
                'name' => $request->name,               
            ]);

            /*
            |--------------------------------------------------------------------------
            | EMPLOYEE CORE
            |--------------------------------------------------------------------------
            */
            $employee->update([
                'primary_role' => $request->primary_role,
                'employment_type' => $request->employment_type,
                'start_date' => $request->start_date,
            ]);

            /*
            |--------------------------------------------------------------------------
            | PROFILE
            |--------------------------------------------------------------------------
            */
            $employee->profile()->updateOrCreate(
                ['employee_id' => $employee->id],
                [
                    'phone' => $request->phone,
                    'gender' => $request->gender,
                    'date_of_birth' => $request->date_of_birth,
                    'nationality' => $request->nationality,
                    'address' => $request->address,
                    'postcode' => $request->postcode,
                    'ni_number' => $request->ni_number,
                    'emergency_contact_name' => $request->emergency_contact_name,
                    'emergency_contact_phone' => $request->emergency_contact_phone,
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | WORK ELIGIBILITY
            |--------------------------------------------------------------------------
            */
            $employee->workEligibility()->updateOrCreate(
                ['employee_id' => $employee->id],
                [
                    'work_status' => $request->work_status,
                    'share_code' => $request->share_code,
                    'expiry_date' => $request->expiry_date,
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | ROLE DETAILS (JSON)
            |--------------------------------------------------------------------------
            */
            $employee->roleDetail()->updateOrCreate(
                ['employee_id' => $employee->id],
                [
                    'role_type' => $request->primary_role,
                    'secondary_skills' => $request->secondary_skills ?? [],
                    'data' => $request->role_detail['data'] ?? [],
                    'data1' => $request->role_detail['data1'] ?? [],
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | PAYROLL
            |--------------------------------------------------------------------------
            */
            $employee->payroll()->updateOrCreate(
                ['employee_id' => $employee->id],
                [
                    'bank_name' => $request->bank_name,
                    'account_number' => $request->account_number,
                    'sort_code' => $request->sort_code,
                    'utr' => $request->utr,
                    'payment_type' => $request->payment_type,
                ]
            );

            DB::commit();

            log_activity(
            'edit_employee_open',
            'Opened employee edit form',
            $employee->user->name . ' profile updated by ' . auth()->user()->name

        );

            return response()->json([
                'success' => true,
                'message' => 'Employee updated successfully',
                'employee' => $employee->fresh([
                    'user',
                    'profile',
                    'roleDetail',
                    'payroll',
                    'workEligibility'
                ])
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
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