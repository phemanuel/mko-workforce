<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use App\Models\EmployeeDocument;

class AdminApplicationController extends Controller
{
    public function index()
    {
        $applications = User::where('role_id', 3)
            ->with('employee')
            ->latest()
            ->get();

        return view('admin.applications.index', compact('applications'));
    }

    public function show($id)
    {
        $user = User::with([
            'employee.profile',
            'employee.workEligibility',
            'employee.roleDetail',
            'employee.payroll',
            'employee.skills',
            'employee.documents',
        ])->findOrFail($id);

        return view('admin.applications.show', compact('user'));
    }

    public function approve($id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'status' => 'active'
        ]);

        return back()->with('success', 'Application approved.');
    }

    public function reject($id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'status' => 'rejected'
        ]);

        return back()->with('success', 'Application rejected.');
    }
}