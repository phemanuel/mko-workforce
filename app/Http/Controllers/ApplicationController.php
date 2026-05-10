<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Employee;
use App\Models\EmployeeProfile;
use App\Models\EmployeeWorkEligibility;
use App\Models\Skill;
use App\Models\EmployeeRoleDetail;
use App\Models\EmployeePayroll;
use App\Models\EmployeeDocument;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    public function index()
    {
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

        return view('application.form', compact('countries'));
    }

    /*
    |--------------------------------------------------------------------------
    | STEP 2
    |--------------------------------------------------------------------------
    */

    public function step2(Request $request)
    {
        $user = auth()->user();

        if ($user->registration_step > 2) {
            return redirect()->route('application.index')
                ->with('error', 'This step is locked. Contact admin for changes.');
        }

        $request->validate([

            // Personal Details
            'full_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required',
            'nationality' => 'required|string|max:255',
            'ni_number' => 'required|string|max:255',
            'address' => 'required|string',
            'postcode' => 'required|string|max:50',
            'phone_no' => 'required|string|max:20',

            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_phone' => 'required|string|max:20',

            // Right To Work
            'work_status' => 'required',
            'share_code' => 'nullable|string|max:255',
            'expiry_date' => 'nullable|date',
        ]);

        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | UPDATE USER NAME
        |--------------------------------------------------------------------------
        */

        $user->update([
            'name' => $request->full_name
        ]);

        /*
        |--------------------------------------------------------------------------
        | EMPLOYEE
        |--------------------------------------------------------------------------
        */

        $employee = Employee::firstOrCreate([
            'user_id' => $user->id
        ]);

        /*
        |--------------------------------------------------------------------------
        | PROFILE
        |--------------------------------------------------------------------------
        */

        EmployeeProfile::updateOrCreate(
            [
                'employee_id' => $employee->id
            ],
            [
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'nationality' => $request->nationality,
                'ni_number' => $request->ni_number,
                'address' => $request->address,
                'postcode' => $request->postcode,
                'phone' => $request->phone_no,
                'emergency_contact_name' => $request->emergency_contact_name,
                'emergency_contact_phone' => $request->emergency_contact_phone,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | WORK ELIGIBILITY
        |--------------------------------------------------------------------------
        */

        EmployeeWorkEligibility::updateOrCreate(
            [
                'employee_id' => $employee->id
            ],
            [
                'work_status' => $request->work_status,
                'share_code' => $request->share_code,
                'expiry_date' => $request->expiry_date,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | NEXT STEP
        |--------------------------------------------------------------------------
        */

        $user->update([
            'registration_step' => 3
        ]);

        return redirect()
            ->route('application.index')
            ->with('success', 'Personal details saved successfully.');
    }

    public function step3(Request $request)
    {

        $user = auth()->user();

        if ($user->registration_step > 3) {
            return redirect()->route('application.index')
                ->with('error', 'This step is locked. Contact admin for changes.');
        }
        
        $request->validate([
            'employment_type' => 'required',
            'start_date' => 'required|date',
            'availability' => 'nullable|string',

            'primary_role' => 'required|string',
            'secondary_skills' => 'nullable|array',

            'skills' => 'nullable|array',
            'skills.*' => 'exists:skills,id',
        ]);

        
        $employee = $user->employee;

        /*
        |--------------------------------------------------------------------------
        | UPDATE EMPLOYEE CORE INFO
        |--------------------------------------------------------------------------
        */

        $employee->update([
            'employment_type' => $request->employment_type,
            'start_date' => $request->start_date,
            'primary_role' => $request->primary_role,
        ]);

        /*
        |--------------------------------------------------------------------------
        | STORE ROLE DETAIL (STEP 4 PREP)
        |--------------------------------------------------------------------------
        */

        EmployeeRoleDetail::updateOrCreate(
            [
                'employee_id' => $employee->id,                
            ],
            [
                'role_type' => $request->primary_role,   
                'secondary_skills' => $request->secondary_skills,             
                'data' => [
                    'availability' => $request->availability,
                ]
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | ATTACH SKILLS (MANY TO MANY)
        |--------------------------------------------------------------------------
        */

        if ($request->filled('skills')) {
            $employee->skills()->sync($request->skills);
        }

        /*
        |--------------------------------------------------------------------------
        | ADVANCE STEP
        |--------------------------------------------------------------------------
        */

        $user->update([
            'registration_step' => 4
        ]);

        return redirect()
            ->route('application.index')
            ->with('success', 'Employment details saved successfully.');
    }

    public function step4(Request $request)
    {
        $user = auth()->user();

        if ($user->registration_step > 4) {
            return redirect()->route('application.index')
                ->with('error', 'This step is locked. Contact admin for changes.');
        }
        
        $request->validate([
            'role_data' => 'nullable|array',
            'badge' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        
        $employee = $user->employee;

        /*
        |--------------------------------------------------------------------------
        | ROLE DATA ARRAY
        |--------------------------------------------------------------------------
        */

        $roleData = $request->role_data ?? [];

        /*
        |--------------------------------------------------------------------------
        | HANDLE SIA BADGE UPLOAD
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('badge')) {

            $roleData['badge'] = $request->file('badge')
                ->store('employee/badges', 'public');
        }

        /*
        |--------------------------------------------------------------------------
        | SAVE ROLE DATA
        |--------------------------------------------------------------------------
        */

        EmployeeRoleDetail::updateOrCreate(
            [
                'employee_id' => $employee->id,
            ],
            [
                'role_type' => $employee->primary_role,
                'data1' => $roleData,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | NEXT STEP
        |--------------------------------------------------------------------------
        */

        $user->update([
            'registration_step' => 5
        ]);

        return redirect()
            ->route('application.index')
            ->with('success', 'Role details saved successfully.');
    }

    public function step5(Request $request)
    {
        $user = auth()->user();

        if ($user->registration_step > 5) {
            return redirect()->route('application.index')
                ->with('error', 'This step is locked. Contact admin for changes.');
        }
        
        $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:50',
            'sort_code' => 'required|string|max:20',
            'utr' => 'nullable|string|max:50',
            'payment_type' => 'required|in:PAYE,Self-Employed',
        ]);

        
        $employee = $user->employee;

        /*
        |--------------------------------------------------------------------------
        | SAVE PAYROLL DATA
        |--------------------------------------------------------------------------
        */

        EmployeePayroll::updateOrCreate(
            [
                'employee_id' => $employee->id,
            ],
            [
                'bank_name' => $request->bank_name,
                'account_number' => $request->account_number,
                'sort_code' => $request->sort_code,
                'utr' => $request->utr,
                'payment_type' => $request->payment_type,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | ADVANCE STEP
        |--------------------------------------------------------------------------
        */

        $user->update([
            'registration_step' => 6
        ]);

        return redirect()
            ->route('application.index')
            ->with('success', 'Payroll details saved successfully.');
    }

    public function step6(Request $request)
    {
        $user = auth()->user();

        if ($user->registration_step > 6) {
            return redirect()->route('application.index')
                ->with('error', 'This step is locked. Contact admin for changes.');
        }
        
        $request->validate([
            'passport' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'ni_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'dbs' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'sia_license' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'certificates' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $employee = auth()->user()->employee;

        /*
        |--------------------------------------------------------------------------
        | DOCUMENT TYPES MAP
        |--------------------------------------------------------------------------
        */

        $documents = [
            'passport' => 'Passport',
            'ni_proof' => 'NI Proof',
            'dbs' => 'DBS',
            'sia_license' => 'SIA Licence',
            'certificates' => 'Certificates',
        ];

        /*
        |--------------------------------------------------------------------------
        | LOOP & STORE DOCUMENTS
        |--------------------------------------------------------------------------
        */

        foreach ($documents as $field => $type) {

            if (request()->hasFile($field)) {

                $path = request()->file($field)
                    ->store('employee/documents', 'public');

                EmployeeDocument::updateOrCreate(
                    [
                        'employee_id' => $employee->id,
                        'document_type' => $type,
                    ],
                    [
                        'file_path' => $path,
                        'verification_status' => 'Pending',
                    ]
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | COMPLETE ONBOARDING
        |--------------------------------------------------------------------------
        */

        auth()->user()->update([
            'registration_step' => 7,
            'status' => 'active',
            'approval_status' => 'pending'
        ]);

        log_activity(
            'employee_registered',
            'New employee registration',
            $user->name . ' completed online registration, pending review.'
        );

        return redirect()
            ->route('application.index')
            ->with('success', 'Documents uploaded successfully. Your application is under review,
             you can logout of the application.');
    }

    public function editStep($step)
    {
        $user = auth()->user();
        $employee = $user->employee;

        if ($user->status !== 'pending') {
            return redirect()->route('application.index')
                ->with('error', 'Your application is locked after approval.');
        }

        return view('applicant.application', compact('step', 'employee'));
    }

}