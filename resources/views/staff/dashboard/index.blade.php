@extends('layouts.staff')

@section('page-title', 'My Dashboard')

@section('content')

<div class="space-y-6">

    <!-- WELCOME HERO -->
    <div class="bg-gradient-to-r from-gray-900 to-black rounded-2xl p-6 md:p-8 text-white shadow-lg">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">

            <div>
                <!-- <p class="text-sm text-gray-300 mb-2">
                    STAFF PORTAL
                </p> -->

                <h1 class="text-3xl font-bold mb-2">
                    Welcome back, {{ auth()->user()->name }}
                </h1>

                <p class="text-gray-300 text-sm md:text-base">
                    Manage your assigned shifts, attendance records, and payment history.
                </p>
            </div>           

        </div>

    </div>

    <!-- KPI CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">

        <!-- UPCOMING -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition">

            <div class="flex items-center justify-between mb-4">

                <div class="w-12 h-12 rounded-xl bg-black text-white flex items-center justify-center">
                    <i class="fas fa-calendar-alt"></i>
                </div>

                <span class="text-xs bg-gray-100 px-3 py-1 rounded-full text-gray-600">
                    Upcoming
                </span>

            </div>

            <h2 class="text-3xl font-bold text-gray-900">
                 {{ $upcomingShifts }}
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Upcoming Shifts
            </p>

        </div>

        <!-- COMPLETED -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition">

            <div class="flex items-center justify-between mb-4">

                <div class="w-12 h-12 rounded-xl bg-blue-600 text-white flex items-center justify-center">
                    <i class="fas fa-check-circle"></i>
                </div>

                <span class="text-xs bg-blue-50 px-3 py-1 rounded-full text-blue-600">
                    Completed
                </span>

            </div>

            <h2 class="text-3xl font-bold text-gray-900">
                 {{ $completedShifts }}
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Completed Shifts
            </p>

        </div>

        <!-- ATTENDANCE -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition">

            <div class="flex items-center justify-between mb-4">

                <div class="w-12 h-12 rounded-xl bg-green-600 text-white flex items-center justify-center">
                    <i class="fas fa-user-clock"></i>
                </div>

                <span class="text-xs bg-green-50 px-3 py-1 rounded-full text-green-600">
                    Attendance
                </span>

            </div>

            <h2 class="text-3xl font-bold text-gray-900">
                {{ $attendanceRate }}%
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Attendance Rate
            </p>

        </div>

         <!-- PAYMENTS -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition">

            <div class="flex items-center justify-between mb-4">

                <div class="w-12 h-12 rounded-xl bg-yellow-500 text-white flex items-center justify-center">
                    <i class="fas fa-clock"></i>
                </div>

                <span class="text-xs bg-yellow-50 px-3 py-1 rounded-full text-yellow-600">
                    Hours
                </span>

            </div>

            <h2 class="text-3xl font-bold text-green-600">
               {{ number_format($workedHours,1) }}
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Hours Worked
            </p>

        </div>

        <!-- PAYMENTS -->
        <!-- <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition">

            <div class="flex items-center justify-between mb-4">

                <div class="w-12 h-12 rounded-xl bg-yellow-500 text-white flex items-center justify-center">
                    <i class="fas fa-wallet"></i>
                </div>

                <span class="text-xs bg-yellow-50 px-3 py-1 rounded-full text-yellow-600">
                    Pending
                </span>

            </div>

            <h2 class="text-3xl font-bold text-green-600">
                £240
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Pending Payments
            </p>

        </div> -->

    </div>

    <!-- MAIN GRID -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        <!-- TODAY SHIFT -->
        <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

            <div class="flex items-center justify-between mb-6">

                <div>
                    <h3 class="text-lg font-bold text-gray-900">
                        Today's Shift
                    </h3>

                    <p class="text-sm text-gray-500">
                        Your currently assigned shift
                    </p>
                </div>

                <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs">

                    {{ $todayStatus }}

                </span>

            </div>

            @if($todayShift)

        <div class="space-y-6">

            <div>

                <h3 class="text-xl font-semibold">

                    {{ $todayShift->shift->title }}

                </h3>

                <p class="text-gray-500">

                    {{ $todayShift->shift->location }}

                </p>

            </div>

            <div class="grid md:grid-cols-3 gap-4">

                <div>

                    <p class="text-xs text-gray-500">

                        Start

                    </p>

                    <p class="font-semibold">

                        {{ \Carbon\Carbon::parse($todayShift->shift->start_time)->format('h:i A') }}

                    </p>

                </div>

                <div>

                    <p class="text-xs text-gray-500">

                        End

                    </p>

                    <p class="font-semibold">

                        {{ \Carbon\Carbon::parse($todayShift->shift->end_time)->format('h:i A') }}

                    </p>

                </div>

                <div>

                    <p class="text-xs text-gray-500">

                        Supervisor

                    </p>

                    <p class="font-semibold">

                        {{ optional($todayShift->shift->supervisor)->name ?? 'Administrator' }}

                    </p>

                </div>

            </div>

            <div class="flex flex-wrap gap-3">

                @if($todayAttendance)

                    @if(in_array($todayAttendance->status,['Pending','Late']))

                        <button
                            id="checkInBtn"
                            class="px-5 py-3 bg-green-600 hover:bg-green-700 rounded-xl text-white">

                            Check In

                        </button>

                    @elseif($todayAttendance->status=='Checked In')

                        <button
                            id="checkOutBtn"
                            class="px-5 py-3 bg-red-600 hover:bg-red-700 rounded-xl text-white">

                            Check Out

                        </button>

                    @else

                        <span class="px-5 py-3 rounded-xl bg-green-100 text-green-700">

                            Shift Completed

                        </span>

                    @endif

                @endif

            </div>

        </div>

    @else

        <div class="text-center py-14">

            <i class="fas fa-calendar-times text-5xl text-gray-300 mb-5"></i>

            <h3 class="text-lg font-semibold">

                No Shift Today

            </h3>

            <p class="text-gray-500 mt-2">

                You're not scheduled for any shift today.

            </p>

        </div>

    @endif

        </div>

        <!-- QUICK ACTIONS -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

            <h3 class="text-lg font-bold text-gray-900 mb-5">
                Quick Actions
            </h3>

            <div class="space-y-6">

    <!-- NEXT SHIFT -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

        <div class="flex items-center justify-between mb-5">

            <div>

                <h3 class="text-lg font-bold text-gray-900">
                    Next Shift
                </h3>

                <p class="text-sm text-gray-500">
                    Your next scheduled assignment
                </p>

            </div>

            <div class="w-11 h-11 rounded-xl bg-black text-white flex items-center justify-center">
                <i class="fas fa-calendar-day"></i>
            </div>

        </div>

        @if($nextShift)

                <div class="space-y-4">

                    <div>

                        <h4 class="font-semibold text-gray-900 text-lg">
                            {{ $nextShift->shift->title }}
                        </h4>

                        <p class="text-sm text-gray-500 mt-1">
                            {{ $nextShift->shift->location }}
                        </p>

                    </div>

                    <div class="grid grid-cols-2 gap-4 text-sm">

                        <div>

                            <p class="text-gray-500">
                                Date
                            </p>

                            <p class="font-medium">
                                {{ \Carbon\Carbon::parse($nextShift->shift->shift_date)->format('D, d M Y') }}
                            </p>

                        </div>

                        <div>

                            <p class="text-gray-500">
                                Time
                            </p>

                            <p class="font-medium">
                                {{ \Carbon\Carbon::parse($nextShift->shift->start_time)->format('h:i A') }}
                                -
                                {{ \Carbon\Carbon::parse($nextShift->shift->end_time)->format('h:i A') }}
                            </p>

                        </div>

                    </div>

                </div>

            @else

                <div class="text-center py-8">

                    <i class="fas fa-calendar-times text-4xl text-gray-300 mb-3"></i>

                    <p class="text-gray-500">

                        No upcoming shift scheduled.

                    </p>

                </div>

            @endif

        </div>

    <!-- QUICK ACTIONS -->

    

            </div>

        </div>

    </div>

    <!-- RECENT PAYMENTS -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

        <div class="flex items-center justify-between mb-6">

            <div>

                <h3 class="text-lg font-bold">

                    Recent Attendance

                </h3>

                <p class="text-sm text-gray-500">

                    Your latest attendance records

                </p>

            </div>

        </div>

        <div class="overflow-x-auto">

            <table class="min-w-full">

                <thead>

                    <tr class="border-b">

                        <th class="text-left py-3 text-sm font-semibold">Shift</th>

                        <th class="text-left py-3 text-sm font-semibold">Date</th>

                        <th class="text-left py-3 text-sm font-semibold">Check In</th>

                        <th class="text-left py-3 text-sm font-semibold">Check Out</th>

                        <th class="text-left py-3 text-sm font-semibold">Status</th>

                    </tr>

                </thead>

                <tbody>

                @forelse($recentAttendance as $attendance)

                    <tr class="border-b hover:bg-gray-50">

                        <td class="py-4">

                            {{ $attendance->shift->title }}

                        </td>

                        <td>

                            {{ \Carbon\Carbon::parse($attendance->shift->shift_date)->format('d M Y') }}

                        </td>

                        <td>

                            {{ optional($attendance->check_in_time)->format('h:i A') ?? '--' }}

                        </td>

                        <td>

                            {{ optional($attendance->check_out_time)->format('h:i A') ?? '--' }}

                        </td>

                        <td>

                            @php

                                $colors = [

                                    'Pending'=>'bg-gray-100 text-gray-700',

                                    'Checked In'=>'bg-green-100 text-green-700',

                                    'Checked Out'=>'bg-blue-100 text-blue-700',

                                    'Late'=>'bg-yellow-100 text-yellow-700',

                                    'Absent'=>'bg-red-100 text-red-700',

                                    'Early Leave'=>'bg-orange-100 text-orange-700'

                                ];

                            @endphp

                            <span class="px-3 py-1 rounded-full text-xs {{ $colors[$attendance->status] }}">

                                {{ $attendance->status }}

                            </span>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="5" class="text-center py-10 text-gray-500">

                            No attendance records found.

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mt-6">

    <div class="flex items-center justify-between mb-6">

        <div>

            <h3 class="text-lg font-bold">

                Recent Assigned Shifts

            </h3>

            <p class="text-sm text-gray-500">

                Your latest shift assignments

            </p>

        </div>

    </div>

    <div class="space-y-4">

        @forelse($recentShifts as $assignment)

            <div class="flex items-center justify-between border rounded-xl p-4 hover:bg-gray-50 transition">

                <div>

                    <h4 class="font-semibold">

                        {{ $assignment->shift->title }}

                    </h4>

                    <p class="text-sm text-gray-500 mt-1">

                        {{ \Carbon\Carbon::parse($assignment->shift->shift_date)->format('D, d M Y') }}

                        •

                        {{ $assignment->shift->location }}

                    </p>

                </div>

                <span class="px-3 py-1 rounded-full text-xs
                    @if($assignment->status=='Assigned')
                        bg-blue-100 text-blue-700
                    @elseif($assignment->status=='Accepted')
                        bg-green-100 text-green-700
                    @elseif($assignment->status=='Completed')
                        bg-gray-900 text-white
                    @else
                        bg-red-100 text-red-700
                    @endif">

                    {{ $assignment->status }}

                </span>

            </div>

        @empty

            <div class="text-center py-10">

                <i class="fas fa-calendar-times text-5xl text-gray-300 mb-4"></i>

                <p class="text-gray-500">

                    No shifts assigned yet.

                </p>

            </div>

        @endforelse

    </div>

</div>


</div>

@endsection