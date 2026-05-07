<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    // LIST ALL EMPLOYEES (ADMIN PANEL)
    public function index()
    {
        $employees = Employee::with('user')->latest()->get();

        return view('admin.employees.index', compact('employees'));
    }

    // VIEW SINGLE EMPLOYEE
    public function show($id)
    {
        $employee = Employee::with('user')->findOrFail($id);

        return view('admin.employees.show', compact('employee'));
    }

    // APPROVE EMPLOYEE
    public function approve($id)
    {
        $employee = Employee::findOrFail($id);

        $employee->update(['status' => 'approved']);

        $employee->user->update(['status' => 'approved']);

        return back()->with('success', 'Employee approved successfully');
    }

    // REJECT EMPLOYEE
    public function reject($id)
    {
        $employee = Employee::findOrFail($id);

        $employee->update(['status' => 'rejected']);

        $employee->user->update(['status' => 'rejected']);

        return back()->with('error', 'Employee rejected');
    }
}