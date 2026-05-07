@extends('layouts.app')

@section('role-label', 'Supervisor Console')

@section('sidebar')

    <a href="/supervisor/dashboard"
       class="block px-4 py-2 rounded hover:bg-gray-800
       {{ request()->routeIs('supervisor.dashboard') ? 'bg-gray-800 text-white' : '' }}">
        📊 Dashboard
    </a>

    <a href="/supervisor/shifts"
       class="block px-4 py-2 rounded hover:bg-gray-800
       {{ request()->routeIs('supervisor.shifts*') ? 'bg-gray-800 text-white' : '' }}">
        📅 My Shifts
    </a>

    <a href="/supervisor/staff"
       class="block px-4 py-2 rounded hover:bg-gray-800
       {{ request()->routeIs('supervisor.staff*') ? 'bg-gray-800 text-white' : '' }}">
        👥 Staff
    </a>

    <a href="/supervisor/attendance"
       class="block px-4 py-2 rounded hover:bg-gray-800
       {{ request()->routeIs('supervisor.attendance*') ? 'bg-gray-800 text-white' : '' }}">
        ⏱ Attendance
    </a>

    <a href="/supervisor/incidents"
       class="block px-4 py-2 rounded hover:bg-gray-800
       {{ request()->routeIs('supervisor.incidents*') ? 'bg-gray-800 text-white' : '' }}">
        ⚠ Incidents
    </a>

    <a href="/supervisor/reports"
       class="block px-4 py-2 rounded hover:bg-gray-800
       {{ request()->routeIs('supervisor.reports*') ? 'bg-gray-800 text-white' : '' }}">
        📄 Reports
    </a>

@endsection