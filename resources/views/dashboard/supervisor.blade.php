@extends('layouts.supervisor')

@section('page-title', 'Supervisor Dashboard')

@section('content')

<!-- KPI ROW -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    <div class="bg-white p-5 rounded-lg shadow border-l-4 border-black">
        <p class="text-sm text-gray-500">Assigned Shifts</p>
        <h2 class="text-2xl font-bold">12</h2>
    </div>

    <div class="bg-white p-5 rounded-lg shadow border-l-4 border-blue-600">
        <p class="text-sm text-gray-500">Active Staff</p>
        <h2 class="text-2xl font-bold">48</h2>
    </div>

    <div class="bg-white p-5 rounded-lg shadow border-l-4 border-red-600">
        <p class="text-sm text-gray-500">Late Check-ins</p>
        <h2 class="text-2xl font-bold text-red-600">3</h2>
    </div>

    <div class="bg-white p-5 rounded-lg shadow border-l-4 border-yellow-500">
        <p class="text-sm text-gray-500">Incidents</p>
        <h2 class="text-2xl font-bold text-yellow-600">2</h2>
    </div>

</div>

<!-- MAIN GRID -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <!-- ACTIVE SHIFTS -->
    <div class="bg-white p-6 rounded-lg shadow">

        <h3 class="font-semibold text-gray-700 mb-4">
            Active Shifts
        </h3>

        <div class="space-y-4 text-sm">

            <div class="flex justify-between border-b pb-2">
                <span>Site A - Security</span>
                <span class="text-green-600 font-medium">In Progress</span>
            </div>

            <div class="flex justify-between border-b pb-2">
                <span>Site B - Cleaning</span>
                <span class="text-yellow-600 font-medium">Pending</span>
            </div>

            <div class="flex justify-between">
                <span>Site C - Support</span>
                <span class="text-red-600 font-medium">Delayed</span>
            </div>

        </div>

    </div>

    <!-- INCIDENT FEED -->
    <div class="bg-white p-6 rounded-lg shadow">

        <h3 class="font-semibold text-gray-700 mb-4">
            Recent Incidents
        </h3>

        <div class="space-y-3 text-sm">

            <p>⚠ Staff late at Site A</p>
            <p>⚠ Missing check-out at Site C</p>
            <p>✔ Incident resolved at Site B</p>

        </div>

    </div>

</div>

@endsection