@extends('layouts.staff')

@section('page-title', 'My Dashboard')

@section('content')

<!-- WELCOME -->
<div class="bg-white p-6 rounded-lg shadow mb-6">

    <h2 class="text-xl font-bold">
        Welcome back, {{ auth()->user()->name }}
    </h2>

    <p class="text-gray-500 text-sm mt-1">
        Manage your shifts, attendance, and payments
    </p>

</div>

<!-- KPI GRID -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">

    <div class="bg-white p-5 rounded-lg shadow border-l-4 border-black">
        <p class="text-sm text-gray-500">Upcoming Shifts</p>
        <h2 class="text-2xl font-bold">3</h2>
    </div>

    <div class="bg-white p-5 rounded-lg shadow border-l-4 border-blue-600">
        <p class="text-sm text-gray-500">Completed Shifts</p>
        <h2 class="text-2xl font-bold">18</h2>
    </div>

    <div class="bg-white p-5 rounded-lg shadow border-l-4 border-green-600">
        <p class="text-sm text-gray-500">Pending Payment</p>
        <h2 class="text-2xl font-bold text-green-600">£240</h2>
    </div>

</div>

<!-- TODAY SHIFT -->
<div class="bg-white p-6 rounded-lg shadow mb-6">

    <h3 class="font-semibold text-gray-700 mb-4">
        Today's Shift
    </h3>

    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">

        <div>
            <p class="font-medium">Site A - Security</p>
            <p class="text-sm text-gray-500">08:00 AM - 06:00 PM</p>
        </div>

        <div class="flex gap-2">

            <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Check In
            </button>

            <button class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                Check Out
            </button>

        </div>

    </div>

</div>

<!-- PAYMENTS -->
<div class="bg-white p-6 rounded-lg shadow">

    <h3 class="font-semibold text-gray-700 mb-4">
        Recent Payments
    </h3>

    <div class="space-y-3 text-sm">

        <div class="flex justify-between border-b pb-2">
            <span>Week 1 - Security</span>
            <span>£120</span>
        </div>

        <div class="flex justify-between border-b pb-2">
            <span>Week 2 - Cleaning</span>
            <span>£110</span>
        </div>

    </div>

</div>

@endsection