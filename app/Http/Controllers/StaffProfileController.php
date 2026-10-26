<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\Employee;

class StaffProfileController extends Controller
{
    /**
     * Display the authenticated staff member's profile.
     */
    public function index()
    {
        $user = auth()->user();

        $employee = Employee::with([
            'user',
            'profile',
            'roleDetail',
            'workEligibility',
            'payroll',
            'documents',
            'skills',
        ])
        ->where('user_id', $user->id)
        ->firstOrFail();

        return view('staff.profile.index', compact('user', 'employee'));
    }

    /**
     * Update permitted personal and emergency contact information.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $employee = Employee::where('user_id', $user->id)
            ->firstOrFail();

        $validated = $request->validate([
            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'date_of_birth' => [
                'nullable',
                'date',
                'before:today',
            ],

            'gender' => [
                'nullable',
                Rule::in(['Male', 'Female', 'Other', 'Prefer not to say']),
            ],

            'nationality' => [
                'nullable',
                'string',
                'max:100',
            ],

            'address' => [
                'nullable',
                'string',
                'max:500',
            ],

            'postcode' => [
                'nullable',
                'string',
                'max:20',
            ],

            'emergency_contact_name' => [
                'nullable',
                'string',
                'max:150',
            ],

            'emergency_contact_phone' => [
                'nullable',
                'string',
                'max:30',
            ],
        ]);

        DB::transaction(function () use ($employee, $validated) {
            $employee->profile()->updateOrCreate(
                ['employee_id' => $employee->id],
                $validated
            );
        });

        return redirect()
            ->route('staff.profile.index')
            ->with('success', 'Your profile has been updated successfully.');
    }
}