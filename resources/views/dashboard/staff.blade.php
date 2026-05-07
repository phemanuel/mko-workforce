@extends('layouts.staff')

@section('page-title', 'My Dashboard')

@section('content')

<!-- WELCOME CARD -->
<div class="bg-white p-6 rounded-lg shadow mb-6">

    <h2 class="text-xl font-bold">
        Welcome back, {{ auth()->user()->name }}
    </h2>

    <p class="text-gray-500 text-sm mt-1">
        Manage your shifts and attendance
    </p>

</div>

<!-- QUICK STATS -->
<div class="grid grid-cols-3 gap-4 mb-6">

    <div class="bg-white p-5 rounded-lg shadow">
        <p class="text-sm text-gray-500">Upcoming Shifts</p>
        <h2 class="text-2xl font-bold">3</h2>
    </div>

    <div class="bg-white p-5 rounded-lg shadow">
        <p class="text-sm text-gray-500">Completed Shifts</p>
        <h2 class="text-2xl font-bold">18</h2>
    </div>

    <div class="bg-white p-5 rounded-lg shadow">
        <p class="text-sm text-gray-500">Pending Payment</p>
        <h2 class="text-2xl font-bold text-green-600">£240</h2>
    </div>

</div>

<!-- TODAY SHIFT -->
<div class="bg-white p-6 rounded-lg shadow mb-6">

    <h3 class="font-semibold mb-4">Today's Shift</h3>

    <div class="flex justify-between items-center">

        <div>
            <p class="font-medium">Site A - Security</p>
            <p class="text-sm text-gray-500">08:00 AM - 06:00 PM</p>
        </div>

        <div class="space-x-2">

            <button class="bg-green-600 text-white px-4 py-2 rounded">
                Check In
            </button>

            <button class="bg-red-600 text-white px-4 py-2 rounded">
                Check Out
            </button>

        </div>

    </div>

</div>

<!-- PAYMENTS SNAPSHOT -->
<div class="bg-white p-6 rounded-lg shadow">

    <h3 class="font-semibold mb-4">Recent Payments</h3>

    <div class="space-y-2 text-sm">

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