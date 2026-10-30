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
     *
     * Standard documents:
     * - Passport
     * - NI Proof
     * - DBS
     * - SIA Licence
     *
     * Other Certification:
     * - Allows multiple files in one submission.
     * - Creates a separate EmployeeDocument record for each file.
     * - Expiry date is optional.
     */
    public function uploadDocument(Request $request)
    {
        $user = auth()->user();

        // Always use the authenticated employee's record.
        $employee = $user->employee;

        if (!$employee) {
            abort(404, 'Your employee profile could not be found.');
        }

        $standardDocumentTypes = [
            'Passport',
            'NI Proof',
            'DBS',
            'SIA Licence',
        ];

        $certificationTypes = [
            'Other Certification',
            'Certificates', // Legacy compatibility.
        ];

        $documentTypes = array_merge(
            $standardDocumentTypes,
            $certificationTypes
        );

        $documentType = $request->input('document_type');

        $isCertification = in_array(
            $documentType,
            $certificationTypes,
            true
        );

        /*
        |--------------------------------------------------------------------------
        | Validate the request
        |--------------------------------------------------------------------------
        |
        | Standard documents require one file.
        | Other Certification requires 1–10 files.
        | Expiry dates are optional for all document types.
        |
        */

        $validated = $request->validate([
            'document_type' => [
                'required',
                'string',
                Rule::in($documentTypes),
            ],

            'document' => [
                Rule::requiredIf(
                    fn () => in_array(
                        $request->input('document_type'),
                        $standardDocumentTypes,
                        true
                    )
                ),
                Rule::prohibitedIf(
                    fn () => in_array(
                        $request->input('document_type'),
                        $certificationTypes,
                        true
                    )
                ),
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:2048',
            ],

            'documents' => [
                Rule::requiredIf(
                    fn () => in_array(
                        $request->input('document_type'),
                        $certificationTypes,
                        true
                    )
                ),
                Rule::prohibitedIf(
                    fn () => in_array(
                        $request->input('document_type'),
                        $standardDocumentTypes,
                        true
                    )
                ),
                'nullable',
                'array',
                'min:1',
                'max:10',
            ],

            'documents.*' => [
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

        $isCertification = in_array(
            $documentType,
            $certificationTypes,
            true
        );

        $expiryDate = !empty($validated['expiry_date'])
            ? $validated['expiry_date']
            : null;

        /*
        |--------------------------------------------------------------------------
        | Prepare files
        |--------------------------------------------------------------------------
        */

        $files = $isCertification
            ? $request->file('documents', [])
            : [$request->file('document')];

        /*
        |--------------------------------------------------------------------------
        | Protect existing standard documents
        |--------------------------------------------------------------------------
        |
        | Standard documents are resubmitted against their existing record.
        | Certifications are additional records and do not replace existing
        | certifications.
        |
        */

        $existingDocument = null;

        if (!$isCertification) {
            $existingDocument = EmployeeDocument::where(
                'employee_id',
                $employee->id
            )
                ->where('document_type', $documentType)
                ->first();

            if ($existingDocument) {
                $currentStatus = strtolower(
                    trim((string) $existingDocument->status)
                );

                $verificationStatus = strtolower(
                    trim((string) $existingDocument->verification_status)
                );

                $isApproved =
                    in_array($currentStatus, ['approved', 'verified'], true)
                    || in_array(
                        $verificationStatus,
                        ['approved', 'verified'],
                        true
                    );

                if ($isApproved) {
                    return redirect()
                        ->route('staff.profile.index')
                        ->with(
                            'error',
                            'This document has already been approved. Please contact an administrator if it needs updating.'
                        );
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Store files and save document records
        |--------------------------------------------------------------------------
        */

        $uploadedPaths = [];
        $oldFilePath = $existingDocument?->file_path;

        try {
            foreach ($files as $file) {
                if (!$file) {
                    continue;
                }

                $path = $file->store(
                    'employee/documents',
                    'public'
                );

                if (!$path) {
                    throw new \RuntimeException(
                        'The uploaded file could not be stored.'
                    );
                }

                $uploadedPaths[] = $path;
            }

            if (count($uploadedPaths) !== count($files)) {
                throw new \RuntimeException(
                    'One or more files could not be uploaded.'
                );
            }

            \Illuminate\Support\Facades\DB::transaction(
                function () use (
                    $employee,
                    $documentType,
                    $expiryDate,
                    $uploadedPaths,
                    $isCertification,
                    $existingDocument
                ) {
                    foreach ($uploadedPaths as $path) {
                        /*
                        * For standard documents, reuse the existing
                        * rejected/pending record when available.
                        *
                        * For certifications, create a separate record
                        * for every uploaded file.
                        */
                        $document = (
                            !$isCertification && $existingDocument
                        )
                            ? $existingDocument
                            : new EmployeeDocument();

                        $document->employee_id = $employee->id;
                        $document->document_type = $documentType;
                        $document->file_path = $path;
                        $document->expiry_date = $expiryDate;

                        // Keep both review-status fields consistent.
                        $document->status = 'pending';
                        $document->verification_status = 'Pending';

                        // Clear the previous review when resubmitting.
                        $document->verified_by = null;
                        $document->verified_at = null;
                        $document->rejection_reason = null;

                        $document->save();
                    }
                }
            );
        } catch (\Throwable $exception) {
            // Remove files uploaded during this request if saving fails.
            if (!empty($uploadedPaths)) {
                Storage::disk('public')->delete($uploadedPaths);
            }

            report($exception);

            return redirect()
                ->route('staff.profile.index')
                ->with(
                    'error',
                    'The document upload could not be completed. Please try again.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Remove the previous file after a successful replacement
        |--------------------------------------------------------------------------
        |
        | Never delete the old file before the database save succeeds.
        | Certification uploads do not replace existing files.
        |
        */

        if (
            !$isCertification
            && $existingDocument
            && $oldFilePath
            && $oldFilePath !== ($uploadedPaths[0] ?? null)
        ) {
            Storage::disk('public')->delete($oldFilePath);
        }

        /*
        |--------------------------------------------------------------------------
        | Return success
        |--------------------------------------------------------------------------
        */

        if ($isCertification) {
            $count = count($uploadedPaths);

            $message = $count === 1
                ? 'Other Certification uploaded successfully and is awaiting review.'
                : $count . ' certifications uploaded successfully and are awaiting review.';
        } else {
            $message = $documentType
                . ' uploaded successfully and is awaiting review.';
        }

        return redirect()
            ->route('staff.profile.index')
            ->with('success', $message);
    }


   public function updatePayrollInformation(Request $request)
    {
        $user = auth()->user();

        $employee = Employee::where('user_id', $user->id)
            ->firstOrFail();

        $payroll = $employee->payroll;

        $validated = $request->validate([
            'payment_type' => [
                'required',
                Rule::in([
                    'PAYE',
                    'Self-Employed',
                    'Contractor',
                ]),
            ],

            'bank_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'account_number' => [
                'nullable',
                'string',
                'max:50',
            ],

            'sort_code' => [
                'nullable',
                'string',
                'max:20',
            ],

            'utr' => [
                'nullable',
                Rule::requiredIf(
                    fn () =>
                        $request->input('payment_type') === 'Self-Employed'
                        && blank($payroll?->utr)
                ),
                'string',
                'regex:/^[0-9]{10}$/',
            ],
        ], [
            'payment_type.required' =>
                'Please select your payment type.',

            'payment_type.in' =>
                'Please select a valid payment type.',

            'utr.required' =>
                'Please enter your 10-digit UTR for self-employed payment.',

            'utr.regex' =>
                'Your UTR must contain exactly 10 digits.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Preserve Existing Encrypted Bank Details
        |--------------------------------------------------------------------------
        |
        | Account number and sort code are intentionally left blank when
        | the employee does not want to change the saved values.
        |
        */

        if (blank($validated['account_number'] ?? null)) {
            unset($validated['account_number']);
        }

        if (blank($validated['sort_code'] ?? null)) {
            unset($validated['sort_code']);
        }

        /*
        |--------------------------------------------------------------------------
        | Handle UTR
        |--------------------------------------------------------------------------
        */

        if ($validated['payment_type'] !== 'Self-Employed') {
            // UTR is not applicable to other payment arrangements.
            $validated['utr'] = null;
        } elseif (blank($validated['utr'] ?? null)) {
            // Preserve an existing UTR if the employee leaves it blank.
            unset($validated['utr']);
        }

        /*
        |--------------------------------------------------------------------------
        | Save Payroll Information
        |--------------------------------------------------------------------------
        */

        $employee->payroll()->updateOrCreate(
            ['employee_id' => $employee->id],
            $validated
        );

        return redirect()
            ->back()
            ->with('success', 'Payroll information updated successfully.');
    }
}