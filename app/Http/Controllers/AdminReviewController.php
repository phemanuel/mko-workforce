<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use App\Models\EmployeeDocument;
use Illuminate\Http\Request;

class AdminReviewController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LIST ALL COMPLETED APPLICATIONS
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $applications = User::where('role_id', 3)
            ->where('registration_step', '>=', 7)
            ->latest()
            ->get();

            log_activity(
                'admin_view_applications',
                'Viewed applications',
                'Admin opened applications queue'
            );

        return view('admin.applications.index', compact('applications'));
    }

    /*
    |--------------------------------------------------------------------------
    | VIEW SINGLE APPLICATION
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $user = User::with([
            'employee.profile',
            'employee.documents',
            'employee.payroll'
        ])->findOrFail($id);

        log_activity(
            'admin_review_application',
            'Reviewed application',
            "Admin opened application for {$user->name}",
            ['user_id' => $user->id]
        );

        return view('admin.applications.show', compact('user'));
    }

    /*
    |--------------------------------------------------------------------------
    | APPROVE APPLICATION
    |--------------------------------------------------------------------------
    */
    public function approve($id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'approval_status' => 'approved',
            'status' => 'active',
        ]);

        log_activity(
            'application_approved',
            'Application approved',
            $user->name . ' has been approved by '  . auth()->user()->name,
            ['user_id' => $user->id]
        );

        return back()->with('success', 'Application approved successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | REJECT APPLICATION
    |--------------------------------------------------------------------------
    */
    public function reject(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'approval_status' => 'rejected',
            'status' => 'pending',
        ]);

        log_activity(
            'application_rejected',
            'Application rejected',
            $user->name . ' has been rejected by '  . auth()->user()->name,
            ['user_id' => $user->id]
        );

        return back()->with('error', 'Application rejected.');
    }

    public function approveDocument($id)
    {
        $doc = EmployeeDocument::findOrFail($id);
        $user = User::where('id', $doc->employee_id)->first();

        $doc->update([
            'status' => 'approved',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
            'rejection_reason' => null,
        ]);

        log_activity(
            'document_approved',
            'Document approved',
            $user->name . ' - ' . $doc->document_type . ' has been approved by ' . auth()->user()->name,
            ['user_id' => $user->id]
        );

        return response()->json([
            'status' => 'approved',
            'message' => 'Document approved successfully'
        ]);
    }

    public function rejectDocument($id)
    {
        
        $doc = EmployeeDocument::findOrFail($id);
        $user = User::where('id', $doc->employee_id)->first();

        $doc->update([
            'status' => 'rejected',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
            'rejection_reason' => 'Rejected by admin'
        ]);

        log_activity(
            'document_rejected',
            'Document rejected',
            $user->name . ' - ' . $doc->document_type . ' has been rejected by ' . auth()->user()->name,
            ['user_id' => $user->id]
        );

        return response()->json([
            'status' => 'rejected',
            'message' => 'Document rejected successfully'
        ]);
    }

}
