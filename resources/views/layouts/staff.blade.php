@extends('layouts.app')

@section('role-label', 'Staff Portal')

@section('sidebar')

    <a href="/staff/dashboard"
       class="block px-4 py-2 rounded hover:bg-gray-800
       {{ request()->routeIs('staff.dashboard') ? 'bg-gray-800 text-white' : '' }}">
        📊 Dashboard
    </a>    

    <a href="/staff/my-shifts"
       class="block px-4 py-2 rounded hover:bg-gray-800
       {{ request()->routeIs('shifts.index*') ? 'bg-gray-800 text-white' : '' }}">
        🧾 My Shifts
    </a>

    <a href="/staff/shifts"
       class="block px-4 py-2 rounded hover:bg-gray-800
       {{ request()->routeIs('staff.shifts*') ? 'bg-gray-800 text-white' : '' }}">
        📅 Available Shifts
    </a>

    <a href="/staff/attendance"
       class="block px-4 py-2 rounded hover:bg-gray-800
       {{ request()->routeIs('staff.attendance*') ? 'bg-gray-800 text-white' : '' }}">
        ⏱ Attendance
    </a>

    <a href="/staff/payments"
       class="block px-4 py-2 rounded hover:bg-gray-800
       {{ request()->routeIs('staff.payments*') ? 'bg-gray-800 text-white' : '' }}">
        💰 Payments
    </a>

    <a href="/staff/profile"
       class="block px-4 py-2 rounded hover:bg-gray-800
       {{ request()->routeIs('staff.profile*') ? 'bg-gray-800 text-white' : '' }}">
        👤 Profile
    </a>

@endsection