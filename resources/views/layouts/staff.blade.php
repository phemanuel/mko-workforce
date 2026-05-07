@extends('layouts.app')

@section('role-label', 'Staff Portal')

@section('sidebar')
    <a href="/staff/dashboard" class="block px-4 py-2 rounded hover:bg-gray-800">📊 Dashboard</a>
    <a href="/staff/shifts" class="block px-4 py-2 rounded hover:bg-gray-800">📅 Available Shifts</a>
    <a href="/staff/my-shifts" class="block px-4 py-2 rounded hover:bg-gray-800">🧾 My Shifts</a>
    <a href="/staff/attendance" class="block px-4 py-2 rounded hover:bg-gray-800">⏱ Attendance</a>
    <a href="/staff/payments" class="block px-4 py-2 rounded hover:bg-gray-800">💰 Payments</a>
    <a href="/staff/profile" class="block px-4 py-2 rounded hover:bg-gray-800">👤 Profile</a>
@endsection