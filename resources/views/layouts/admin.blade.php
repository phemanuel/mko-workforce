@extends('layouts.app')

@section('role-label', 'Admin Control Panel')

@section('sidebar')
    <a href="/admin/dashboard" class="block px-4 py-2 rounded hover:bg-gray-800">📊 Dashboard</a>
    <a href="/admin/employees" class="block px-4 py-2 rounded hover:bg-gray-800">👷 Employees</a>
    <a href="/admin/shifts" class="block px-4 py-2 rounded hover:bg-gray-800">📅 Shifts</a>
    <a href="/admin/attendance" class="block px-4 py-2 rounded hover:bg-gray-800">⏱ Attendance</a>
    <a href="/admin/payroll" class="block px-4 py-2 rounded hover:bg-gray-800">💰 Payroll</a>
    <a href="/admin/compliance" class="block px-4 py-2 rounded hover:bg-gray-800">🛡 Compliance</a>
    <a href="/admin/incidents" class="block px-4 py-2 rounded hover:bg-gray-800">⚠ Incidents</a>
@endsection