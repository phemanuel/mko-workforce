@extends('layouts.app')

@section('role-label', 'Admin Control Panel')

@section('sidebar')

    <a href="/admin/dashboard"
       class="block px-4 py-2 rounded hover:bg-gray-800
       {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800 text-white' : '' }}">
        📊 Dashboard
    </a>

    <a href="/admin/applications"
       class="block px-4 py-2 rounded hover:bg-gray-800
       {{ request()->routeIs('admin.applications*') ? 'bg-gray-800 text-white' : '' }}">
        📥 Applications
    </a>

    <a href="/admin/employees"
       class="block px-4 py-2 rounded hover:bg-gray-800
       {{ request()->routeIs('admin.employees*') ? 'bg-gray-800 text-white' : '' }}">
        👷 Employees
    </a>

    <a href="/admin/shifts"
       class="block px-4 py-2 rounded hover:bg-gray-800
       {{ request()->routeIs('shifts.index*') ? 'bg-gray-800 text-white' : '' }}">
        📅 Shifts
    </a>

    <a href="/admin/attendance"
       class="block px-4 py-2 rounded hover:bg-gray-800
       {{ request()->routeIs('admin.attendance*') ? 'bg-gray-800 text-white' : '' }}">
        ⏱ Attendance
    </a>

    <a href="/admin/payroll"
       class="block px-4 py-2 rounded hover:bg-gray-800
       {{ request()->routeIs('admin.payroll*') ? 'bg-gray-800 text-white' : '' }}">
        💰 Payroll
    </a>

    <a href="/admin/compliance"
       class="block px-4 py-2 rounded hover:bg-gray-800
       {{ request()->routeIs('admin.compliance*') ? 'bg-gray-800 text-white' : '' }}">
        🛡 Compliance
    </a>

    <a href="/admin/incidents"
       class="block px-4 py-2 rounded hover:bg-gray-800
       {{ request()->routeIs('admin.incidents*') ? 'bg-gray-800 text-white' : '' }}">
        ⚠ Incidents
    </a>

@endsection