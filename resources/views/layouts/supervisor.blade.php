@extends('layouts.app')

@section('role-label', 'Supervisor Console')

@section('sidebar')
    <a href="/supervisor/dashboard" class="block px-4 py-2 rounded hover:bg-gray-800">📊 Dashboard</a>
    <a href="/supervisor/shifts" class="block px-4 py-2 rounded hover:bg-gray-800">📅 My Shifts</a>
    <a href="/supervisor/staff" class="block px-4 py-2 rounded hover:bg-gray-800">👥 Staff</a>
    <a href="/supervisor/attendance" class="block px-4 py-2 rounded hover:bg-gray-800">⏱ Attendance</a>
    <a href="/supervisor/incidents" class="block px-4 py-2 rounded hover:bg-gray-800">⚠ Incidents</a>
    <a href="/supervisor/reports" class="block px-4 py-2 rounded hover:bg-gray-800">📄 Reports</a>
@endsection