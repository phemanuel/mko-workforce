@extends('layouts.admin')

<!-- @section('page-title', 'Admin Dashboard') -->

@section('content')

<!-- KPI GRID -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

    <div class="bg-white p-5 rounded-lg shadow border-l-4 border-black">
        <p class="text-sm text-gray-500">Total Employees</p>
        <h2 class="text-2xl font-bold">128</h2>
        <p class="text-xs text-gray-400 mt-1">All registered staff</p>
    </div>

    <div class="bg-white p-5 rounded-lg shadow border-l-4 border-blue-600">
        <p class="text-sm text-gray-500">Active Shifts</p>
        <h2 class="text-2xl font-bold">34</h2>
        <p class="text-xs text-gray-400 mt-1">Currently running</p>
    </div>

    <div class="bg-white p-5 rounded-lg shadow border-l-4 border-red-600">
        <p class="text-sm text-gray-500">Pending Approvals</p>
        <h2 class="text-2xl font-bold text-red-600">12</h2>
        <p class="text-xs text-gray-400 mt-1">Requires action</p>
    </div>

    <div class="bg-white p-5 rounded-lg shadow border-l-4 border-yellow-500">
        <p class="text-sm text-gray-500">Compliance Alerts</p>
        <h2 class="text-2xl font-bold text-yellow-600">5</h2>
        <p class="text-xs text-gray-400 mt-1">Expiring documents</p>
    </div>

</div>

<!-- MAIN DASHBOARD SECTION -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">

    <!-- RECENT ACTIVITY -->
    <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow">

        <h3 class="font-semibold text-gray-700 mb-4">
            Recent Activity
        </h3>

        <div class="space-y-4 text-sm">

            <div class="flex justify-between border-b pb-2">
                <span>✔ New employee registered</span>
                <span class="text-gray-400">John Doe</span>
            </div>

            <div class="flex justify-between border-b pb-2">
                <span>⚠ DBS expiring soon</span>
                <span class="text-yellow-600">3 staff</span>
            </div>

            <div class="flex justify-between border-b pb-2">
                <span>✔ Shift approved</span>
                <span class="text-gray-400">Site A</span>
            </div>

            <div class="flex justify-between">
                <span>⚠ Late check-in flagged</span>
                <span class="text-red-600">Site C</span>
            </div>

        </div>

    </div>

    <!-- QUICK ACTIONS -->
    <div class="bg-white p-6 rounded-lg shadow">

        <h3 class="font-semibold text-gray-700 mb-4">
            Quick Actions
        </h3>

        <div class="space-y-3">

            <a href="/admin/employees"
               class="block bg-black text-white text-center py-2 rounded hover:bg-gray-800">
                Manage Employees
            </a>

            <a href="/admin/shifts"
               class="block bg-red-600 text-white text-center py-2 rounded hover:bg-red-700">
                Create Shift
            </a>

            <a href="/admin/compliance"
               class="block bg-gray-800 text-white text-center py-2 rounded hover:bg-black">
                Compliance Check
            </a>

        </div>

    </div>

</div>

<!-- SYSTEM STATUS -->
<div class="mt-6 bg-white p-6 rounded-lg shadow">

    <h3 class="font-semibold mb-4">System Status</h3>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">

        <div class="p-4 bg-gray-50 rounded">
            <p class="text-gray-500">Payroll Engine</p>
            <p class="font-semibold text-green-600">Active</p>
        </div>

        <div class="p-4 bg-gray-50 rounded">
            <p class="text-gray-500">GPS Tracking</p>
            <p class="font-semibold text-green-600">Running</p>
        </div>

        <div class="p-4 bg-gray-50 rounded">
            <p class="text-gray-500">Compliance System</p>
            <p class="font-semibold text-yellow-600">Monitoring</p>
        </div>

    </div>

</div>

@endsection