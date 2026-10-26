<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\Employee;
use App\Models\EmployeeDocument;
use Illuminate\Support\Facades\Storage;

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

    /**
     * Upload or resubmit an employee document.
     */
    public function uploadDocument(Request $request)
    {
        $user = auth()->user();

        // Always use the authenticated employee's record.
        $employee = $user->employee;

        if (!$employee) {
            abort(404, 'Your employee profile could not be found.');
        }

        $documentTypes = [
            'Passport',
            'NI Proof',
            'DBS',
            'SIA Licence',
            'Certificates',
        ];

        $validated = $request->validate([
            'document_type' => [
                'required',
                'string',
                Rule::in($documentTypes),
            ],

            'document' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:2048',
            ],

            'expiry_date' => [
                'nullable',
                'date',
            ],
        ]);

        $documentType = $validated['document_type'];

        // Find only this employee's record for the selected document type.
        $existingDocument = EmployeeDocument::where(
            'employee_id',
            $employee->id
        )
            ->where('document_type', $documentType)
            ->first();

        // Do not replace a document that has already been approved.
        if (
            $existingDocument &&
            strtolower((string) $existingDocument->status) === 'approved'
        ) {
            return redirect()
                ->route('staff.profile.index')
                ->with(
                    'error',
                    'This document has already been approved. Please contact an administrator if it needs updating.'
                );
        }

        $path = $request->file('document')
            ->store('employee/documents', 'public');

        try {
            $document = $existingDocument ?? new EmployeeDocument();

            $document->fill([
                'employee_id' => $employee->id,
                'document_type' => $documentType,
                'file_path' => $path,
                'expiry_date' => $validated['expiry_date'] ?? null,

                // Keep the administrator's existing workflow compatible.
                'status' => 'pending',
                'verification_status' => 'Pending',

                // Clear the previous review when resubmitting.
                'verified_by' => null,
                'verified_at' => null,
                'rejection_reason' => null,
            ]);

            $document->save();

        } catch (\Throwable $exception) {
            Storage::disk('public')->delete($path);

            throw $exception;
        }

        return redirect()
            ->route('staff.profile.index')
            ->with(
                'success',
                $documentType . ' uploaded successfully and is awaiting review.'
            );
    }
}