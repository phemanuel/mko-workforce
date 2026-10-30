<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\EmployeeDocument;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

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
            'skills',
        ]);

        // Load documents separately, newest first.
        $employee->load([
            'documents' => function ($query) {
                $query->orderByDesc('created_at');
            },
        ]);

        log_activity(
            'edit_employee_open',
            'Opened employee edit form',
            ($employee->user?->name ?? 'Unknown Employee')
                . ' edit form opened by '
                . (auth()->user()?->name ?? 'Unknown User')
        );

        return response()->json($employee);
    }

     public function update(Request $request, Employee $employee)
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */
    
        $validated = $request->validate([
            'name' => 'required|string|max:255',
    
            'primary_role' => [
                'required',
                'string',
                'in:SIA Security,Cleaning Services,Companion Support,Back Support Services',
            ],
    
            'employment_type' => 'nullable|in:Full-Time,Part-Time,Casual',
            'start_date' => 'nullable|date',
    
            'phone' => 'nullable|string|max:30',
            'gender' => 'nullable|in:Male,Female,Other',
            'date_of_birth' => 'nullable|date',
            'nationality' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:2000',
            'postcode' => 'nullable|string|max:20',
            'ni_number' => 'nullable|string|max:50',
    
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:30',
    
            'work_status' => 'nullable|in:UK Citizen,BRP,Visa',
            'share_code' => 'nullable|string|max:100',
            'expiry_date' => 'nullable|date',
    
            'secondary_skills' => 'nullable|array',
            'secondary_skills.*' => 'string|max:100',
    
            'role_detail' => 'sometimes|array',
            'role_detail.data' => 'sometimes|array',
            'role_detail.data1' => 'sometimes|array',
    
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:100',
            'sort_code' => 'nullable|string|max:20',
            'utr' => 'nullable|string|max:100',
            'payment_type' => 'nullable|in:PAYE,Self-Employed',
        ], [
            'name.required' => 'Please enter the employee\'s full name.',
            'name.string' => 'The employee name must be valid text.',
            'name.max' => 'The employee name cannot exceed 255 characters.',
    
            'primary_role.required' => 'Please select the employee\'s primary role.',
            'primary_role.in' => 'Please select a valid primary role.',
    
            'employment_type.in' => 'Please select a valid employment type.',
            'start_date.date' => 'Please enter a valid employment start date.',
    
            'gender.in' => 'Please select a valid gender.',
            'date_of_birth.date' => 'Please enter a valid date of birth.',
    
            'expiry_date.date' => 'Please enter a valid eligibility expiry date.',
    
            'secondary_skills.array' => 'Please select valid secondary skills.',
            'secondary_skills.*.string' => 'Each secondary skill must be valid.',
    
            'payment_type.in' => 'Please select a valid payment type.',
        ]);
    
        /*
        |--------------------------------------------------------------------------
        | DATABASE TRANSACTION
        |--------------------------------------------------------------------------
        */
    
        DB::beginTransaction();
    
        try {
    
            /*
            |--------------------------------------------------------------------------
            | USER UPDATE
            |--------------------------------------------------------------------------
            */
    
            $employee->user()->update([
                'name' => $validated['name'],
            ]);
    
            /*
            |--------------------------------------------------------------------------
            | EMPLOYEE CORE
            |--------------------------------------------------------------------------
            */
    
            $employee->update([
                'primary_role' => $validated['primary_role'],
                'employment_type' => $validated['employment_type'] ?? null,
                'start_date' => $validated['start_date'] ?? null,
            ]);
    
            /*
            |--------------------------------------------------------------------------
            | PROFILE
            |--------------------------------------------------------------------------
            */
    
            $employee->profile()->updateOrCreate(
                ['employee_id' => $employee->id],
                [
                    'phone' => $validated['phone'] ?? null,
                    'gender' => $validated['gender'] ?? null,
                    'date_of_birth' => $validated['date_of_birth'] ?? null,
                    'nationality' => $validated['nationality'] ?? null,
                    'address' => $validated['address'] ?? null,
                    'postcode' => $validated['postcode'] ?? null,
                    'ni_number' => $validated['ni_number'] ?? null,
                    'emergency_contact_name' =>
                        $validated['emergency_contact_name'] ?? null,
                    'emergency_contact_phone' =>
                        $validated['emergency_contact_phone'] ?? null,
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
                    'work_status' => $validated['work_status'] ?? null,
                    'share_code' => $validated['share_code'] ?? null,
                    'expiry_date' => $validated['expiry_date'] ?? null,
                ]
            );
    
            /*
            |--------------------------------------------------------------------------
            | ROLE DETAILS
            |--------------------------------------------------------------------------
            */
    
            $employee->roleDetail()->updateOrCreate(
                ['employee_id' => $employee->id],
                [
                    // Use the validated primary role.
                    'role_type' => $validated['primary_role'],
    
                    'secondary_skills' =>
                        $validated['secondary_skills'] ?? [],
    
                    'data' =>
                        $validated['role_detail']['data'] ?? [],
    
                    'data1' =>
                        $validated['role_detail']['data1'] ?? [],
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
                    'bank_name' => $validated['bank_name'] ?? null,
                    'account_number' => $validated['account_number'] ?? null,
                    'sort_code' => $validated['sort_code'] ?? null,
                    'utr' => $validated['utr'] ?? null,
                    'payment_type' => $validated['payment_type'] ?? null,
                ]
            );
    
            /*
            |--------------------------------------------------------------------------
            | ACTIVITY LOG
            |--------------------------------------------------------------------------
            */
    
            log_activity(
                'edit_employee',
                'Updated employee profile',
                $validated['name'] . ' profile updated by '
                    . auth()->user()->name
            );
    
            /*
            |--------------------------------------------------------------------------
            | COMMIT
            |--------------------------------------------------------------------------
            */
    
            DB::commit();
    
            /*
            |--------------------------------------------------------------------------
            | SUCCESS RESPONSE
            |--------------------------------------------------------------------------
            */
    
            return response()->json([
                'success' => true,
                'message' => 'Employee profile updated successfully.',
    
                'employee' => $employee->fresh([
                    'user',
                    'profile',
                    'roleDetail',
                    'payroll',
                    'workEligibility',
                ]),
            ]);
    
        } catch (\Throwable $e) {
    
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
    
            // Record the technical error for debugging without exposing
            // database details to the employee-management interface.
            report($e);
    
            return response()->json([
                'success' => false,
                'message' => 'We could not update this employee. Please try again. If the problem continues, contact your administrator.',
            ], 500);
        }
    }

    /**
     * Upload or resubmit an employee document from the admin employee editor.
     *
     * Standard documents reuse their existing record when available.
     * Other Certification allows multiple files and creates a record per file.
     */
    public function uploadDocument(Request $request, Employee $employee)
    {
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
        | VALIDATION
        |--------------------------------------------------------------------------
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
        ], [
            'document_type.required' => 'Please select a document type.',
            'document_type.in' => 'Please select a valid document type.',

            'document.required' => 'Please select a document file.',
            'document.mimes' => 'The document must be a JPG, JPEG, PNG or PDF file.',
            'document.max' => 'The document must not exceed 2 MB.',

            'documents.required' => 'Please select at least one certification file.',
            'documents.min' => 'Please select at least one certification file.',
            'documents.max' => 'You can upload a maximum of 10 certification files.',
            'documents.*.mimes' => 'Each certification must be a JPG, JPEG, PNG or PDF file.',
            'documents.*.max' => 'Each certification must not exceed 2 MB.',

            'expiry_date.date' => 'Please enter a valid expiry date.',
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
        | PREPARE FILES
        |--------------------------------------------------------------------------
        */

        $files = $isCertification
            ? $request->file('documents', [])
            : [$request->file('document')];

        /*
        |--------------------------------------------------------------------------
        | FIND EXISTING STANDARD DOCUMENT
        |--------------------------------------------------------------------------
        |
        | Standard documents reuse their existing record when available.
        | Certifications always create additional records.
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
                    return response()->json([
                        'success' => false,
                        'message' => 'This document has already been approved. Please contact the appropriate administrator if it needs updating.',
                    ], 422);
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | STORE FILES AND SAVE RECORDS
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

            DB::transaction(function () use (
                $employee,
                $documentType,
                $expiryDate,
                $uploadedPaths,
                $isCertification,
                $existingDocument
            ) {
                foreach ($uploadedPaths as $path) {
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

                    // Reset the previous review when resubmitting.
                    $document->verified_by = null;
                    $document->verified_at = null;
                    $document->rejection_reason = null;

                    $document->save();
                }
            });
        } catch (\Throwable $exception) {
            // Remove newly uploaded files if saving the records fails.
            if (!empty($uploadedPaths)) {
                Storage::disk('public')->delete($uploadedPaths);
            }

            report($exception);

            return response()->json([
                'success' => false,
                'message' => 'The document upload could not be completed. Please try again.',
            ], 500);
        }

        /*
        |--------------------------------------------------------------------------
        | REMOVE OLD FILE AFTER SUCCESSFUL REPLACEMENT
        |--------------------------------------------------------------------------
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
        | SUCCESS MESSAGE
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

        /*
        |--------------------------------------------------------------------------
        | RETURN REFRESHED DOCUMENTS AS JSON
        |--------------------------------------------------------------------------
        |
        | The admin JavaScript expects a JSON response, not a redirect.
        */

        $documents = EmployeeDocument::where(
            'employee_id',
            $employee->id
        )
            ->orderByDesc('created_at')
            ->get([
                'id',
                'employee_id',
                'document_type',
                'file_path',
                'expiry_date',
                'status',
                'verification_status',
                'rejection_reason',
                'created_at',
            ]);

        return response()->json([
            'success' => true,
            'message' => $message,
            'documents' => $documents,
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