<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Employee;
use App\Models\EmployeeProfile;
use App\Models\EmployeeDocument;

class ApplicationController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('application.form', compact('user'));
    }

    /**
     * STEP 1 - PERSONAL + EMPLOYMENT DETAILS
     */
    public function storeStep1(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'address' => 'required',
            'date_of_birth' => 'required|date',
        ]);

        $user = Auth::user();

        // EMPLOYEE RECORD
        $employee = Employee::updateOrCreate(
            ['user_id' => $user->id],
            [
                'phone' => $request->phone,
                'address' => $request->address,
                'date_of_birth' => $request->date_of_birth,
                'status' => 'pending',
            ]
        );

        // UPDATE STEP
        $user->update([
            'registration_step' => 3
        ]);

        return redirect()->route('complete.application')
            ->with('success', 'Step 1 completed. Continue application.');
    }

    /**
     * STEP 2 - DOCUMENTS UPLOAD
     */
    public function storeStep2(Request $request)
    {
        $request->validate([
            'ni_number' => 'nullable',
            'passport' => 'nullable|file',
            'dbs' => 'nullable|file',
        ]);

        $user = Auth::user();

        $employee = Employee::where('user_id', $user->id)->first();

        // SAVE DOCUMENTS
        if ($request->hasFile('passport')) {
            $passportPath = $request->file('passport')->store('documents');
        }

        if ($request->hasFile('dbs')) {
            $dbsPath = $request->file('dbs')->store('documents');
        }

        EmployeeDocument::create([
            'employee_id' => $employee->id,
            'passport' => $passportPath ?? null,
            'dbs' => $dbsPath ?? null,
        ]);

        // UPDATE STEP
        $user->update([
            'registration_step' => 4
        ]);

        return redirect()->route('complete.application')
            ->with('success', 'Documents uploaded successfully.');
    }
}