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

        return back()->with('success', 'Application rejected.');
    }

    public function approveDocument($id)
    {
        $doc = EmployeeDocument::findOrFail($id);

        $doc->update([
            'status' => 'approved',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
            'rejection_reason' => null,
        ]);

        return response()->json([
            'status' => 'approved',
            'message' => 'Document approved successfully'
        ]);
    }

    public function rejectDocument($id)
    {
        $doc = EmployeeDocument::findOrFail($id);

        $doc->update([
            'status' => 'rejected',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
            'rejection_reason' => 'Rejected by admin'
        ]);

        return response()->json([
            'status' => 'rejected',
            'message' => 'Document rejected successfully'
        ]);
    }

}
