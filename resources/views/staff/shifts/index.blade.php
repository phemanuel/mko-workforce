@extends('layouts.staff')

@section('page-title', 'My Shifts')

@section('content')

<div class="space-y-6">

    <!-- PAGE HEADER -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    My Shifts
                </h1>

                <p class="text-sm text-gray-500 mt-1">
                    View and manage all assigned shifts
                </p>
            </div>

        </div>

    </div>

    <!-- SUCCESS MESSAGE -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    <!-- STATUS SUMMARY -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">

        <!-- ASSIGNED -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">

            <div class="flex items-center justify-between mb-4">

                <div class="w-12 h-12 rounded-xl bg-yellow-100 text-yellow-600 flex items-center justify-center">
                    <i class="fas fa-clock"></i>
                </div>

                <span class="text-xs bg-yellow-50 text-yellow-700 px-3 py-1 rounded-full">
                    Pending
                </span>

            </div>

            <h2 class="text-3xl font-bold text-gray-900">
                {{ $pendingShifts }}
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Pending Shifts
            </p>

        </div>

        <!-- ACCEPTED -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">

            <div class="flex items-center justify-between mb-4">

                <div class="w-12 h-12 rounded-xl bg-green-100 text-green-600 flex items-center justify-center">
                    <i class="fas fa-check-circle"></i>
                </div>

                <span class="text-xs bg-green-50 text-green-700 px-3 py-1 rounded-full">
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

        <!-- DECLINED -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">

            <div class="flex items-center justify-between mb-4">

                <div class="w-12 h-12 rounded-xl bg-red-100 text-red-600 flex items-center justify-center">
                    <i class="fas fa-times-circle"></i>
                </div>

                <span class="text-xs bg-red-50 text-red-700 px-3 py-1 rounded-full">
                    Today
                </span>

            </div>

            <h2 class="text-3xl font-bold text-gray-900">
                {{ $todayShifts }}
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Todays Shifts
            </p>

        </div>

        <!-- COMPLETED -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">

            <div class="flex items-center justify-between mb-4">

                <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                    <i class="fas fa-briefcase"></i>
                </div>

                <span class="text-xs bg-blue-50 text-blue-700 px-3 py-1 rounded-full">
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

    </div>

        <!-- AVAILABLE SHIFTS -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">

            <div>
                <h3 class="text-lg font-bold text-gray-900">
                    Available Shifts
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    Open opportunities matching your assigned role.
                </p>
            </div>

            <span class="px-3 py-2 rounded-xl bg-blue-50 text-blue-700 text-sm font-semibold">
                {{ $availableShifts->count() }} Available
            </span>

        </div>

        @forelse($availableShifts as $shift)

            @php
                $remainingPositions = max(
                    0,
                    $shift->required_staff - $shift->occupied_positions
                );
            @endphp

            <div class="border border-gray-100 rounded-2xl p-5 mb-4 hover:border-blue-200 hover:shadow-sm transition">

                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">

                    <div class="flex-1">

                        <div class="flex flex-wrap items-center gap-2 mb-2">

                            <h4 class="font-bold text-gray-900 text-lg">
                                {{ $shift->title }}
                            </h4>

                            <span class="text-xs px-3 py-1 rounded-full bg-green-50 text-green-700">
                                {{ $shift->status }}
                            </span>

                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 text-sm text-gray-500">

                            <div>
                                <i class="fas fa-calendar mr-2"></i>
                                {{ \Carbon\Carbon::parse($shift->shift_date)->format('D, d M Y') }}
                            </div>

                            <div>
                                <i class="fas fa-clock mr-2"></i>
                                {{ \Carbon\Carbon::parse($shift->start_time)->format('h:i A') }}
                                -
                                {{ \Carbon\Carbon::parse($shift->end_time)->format('h:i A') }}
                            </div>

                            <div>
                                <i class="fas fa-map-marker-alt mr-2"></i>
                                {{ $shift->location ?? 'Location not specified' }}
                            </div>

                            <div>
                                <i class="fas fa-user-tie mr-2"></i>
                                {{ $shift->role_required }}
                            </div>

                            <div>
                                <i class="fas fa-users mr-2"></i>
                                {{ $remainingPositions }} position(s) remaining
                            </div>

                            <div>
                                <i class="fas fa-user-shield mr-2"></i>
                                {{ optional($shift->supervisor)->name ?? 'Administrator' }}
                            </div>

                        </div>

                        @if($shift->dress_code)
                            <div class="mt-4 bg-amber-50 border border-amber-100 rounded-xl p-3">

                                <p class="text-xs font-semibold text-amber-800 mb-1">
                                    <i class="fas fa-tshirt mr-1"></i>
                                    Dress Code
                                </p>

                                <p class="text-sm text-gray-700">
                                    {{ $shift->dress_code }}
                                </p>

                            </div>
                        @endif

                    </div>

                    <div class="flex sm:flex-row lg:flex-col gap-2">

                        <button
                            type="button"
                            class="viewAvailableShift px-5 py-2.5 rounded-xl border border-gray-200 text-gray-700 hover:bg-gray-50 text-sm font-semibold"
                            data-id="{{ $shift->id }}"
                            data-title="{{ $shift->title }}"
                            data-description="{{ $shift->description }}"
                            data-date="{{ \Carbon\Carbon::parse($shift->shift_date)->format('D, d M Y') }}"
                            data-start="{{ \Carbon\Carbon::parse($shift->start_time)->format('h:i A') }}"
                            data-end="{{ \Carbon\Carbon::parse($shift->end_time)->format('h:i A') }}"
                            data-location="{{ $shift->location }}"
                            data-role="{{ $shift->role_required }}"
                            data-dress-code="{{ $shift->dress_code }}"
                            data-instructions="{{ $shift->instructions }}"
                            data-notes="{{ $shift->notes }}"
                            data-supervisor="{{ optional($shift->supervisor)->name ?? 'Administrator' }}"
                            data-remaining="{{ $remainingPositions }}"
                            data-rate="{{ $shift->hourly_rate ?? 0 }}">

                            <i class="fas fa-eye mr-2"></i>
                            View Details

                        </button>

                    </div>

                </div>

            </div>

        @empty

            <div class="text-center py-12">

                <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-calendar-check text-2xl text-gray-400"></i>
                </div>

                <h4 class="font-semibold text-gray-800">
                    No Available Shifts
                </h4>

                <p class="text-sm text-gray-500 mt-2">
                    There are currently no open shifts matching your role.
                </p>

            </div>

        @endforelse

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

                @if($todayShift->shift->dress_code)
                    <div class="mt-3 bg-amber-50 border border-amber-100 rounded-xl p-3">
                        <div class="flex items-center gap-2 text-amber-800 text-sm font-semibold">
                            <i class="fas fa-tshirt"></i>
                            Dress Code
                        </div>

                        <p class="text-sm text-gray-700 mt-1">
                            {{ $todayShift->shift->dress_code }}
                        </p>
                    </div>
                @endif

            </div>

            <div class="grid md:grid-cols-3 gap-4">

                <div>

                    <p class="text-xs text-gray-500">

                        Shift Start

                    </p>

                    <p class="font-semibold">

                        {{ \Carbon\Carbon::parse($todayShift->shift->start_time)->format('h:i A') }}

                    </p>

                </div>

                <div>

                    <p class="text-xs text-gray-500">

                        Shift End 

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

                    @php

                        $buttonText = '';
                        $buttonClass = '';

                        switch ($attendanceAction) {

                            case 'checkin':
                                $buttonText = 'Check In';
                                $buttonClass = $todayAttendance->status == 'Late'
                                    ? 'bg-yellow-600 hover:bg-yellow-700'
                                    : 'bg-green-600 hover:bg-green-700';
                                break;

                            case 'checkout':
                                $buttonText = 'Check Out';
                                $buttonClass = 'bg-red-600 hover:bg-red-700';
                                break;

                            default:
                                $buttonText = 'Shift Completed';
                                $buttonClass = 'bg-gray-500';

                        }

                    @endphp

                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 mt-6">

                        <!-- Attendance Summary -->

                        <div class="grid grid-cols-3 gap-4 flex-1">

                            <!-- Check In -->

                            <div class="bg-gray-50 rounded-2xl p-4 border">

                                <p class="text-xs uppercase tracking-wide text-gray-500">

                                    Check In

                                </p>

                                <p class="mt-2 text-lg font-semibold text-slate-800">

                                    {{ $todayAttendance->check_in_time
                                        ? $todayAttendance->check_in_time->format('g:i A')
                                        : '--' }}

                                </p>

                            </div>

                            <!-- Check Out -->

                            <div class="bg-gray-50 rounded-2xl p-4 border">

                                <p class="text-xs uppercase tracking-wide text-gray-500">

                                    Check Out

                                </p>

                                <p class="mt-2 text-lg font-semibold text-slate-800">

                                    {{ $todayAttendance->check_out_time
                                        ? $todayAttendance->check_out_time->format('g:i A')
                                        : '--' }}

                                </p>

                            </div>

                            <!-- Worked Hours -->

                            <div class="bg-gray-50 rounded-2xl p-4 border">

                                <p class="text-xs uppercase tracking-wide text-gray-500">

                                    Worked

                                </p>

                                <p class="mt-2 text-lg font-semibold text-slate-800">

                                    @if(!$todayAttendance->check_in_time)

                                        --

                                    @elseif($todayAttendance->check_in_time && !$todayAttendance->check_out_time)

                                        <span class="text-blue-600">

                                            Currently Working

                                        </span>

                                    @else

                                         {{ number_format($todayAttendance->worked_hours, 2) }} hrs

                                    @endif

                                </p>

                            </div>

                        </div>

                        <!-- Attendance Button -->

                        <div>

                            <button
                                id="attendanceBtn"
                                data-action="{{ $attendanceAction }}"
                                data-attendance="{{ $todayAttendance->id }}"
                                class="px-6 py-4 rounded-2xl text-white font-semibold transition-all duration-300 {{ $buttonClass }}
                                    disabled:opacity-50 disabled:cursor-not-allowed"
                                {{ $attendanceAction == 'completed' ? 'disabled' : '' }}>

                                <span class="btn-text">

                                    {{ $buttonText }}

                                </span>

                            </button>

                        </div>

                    </div>

                    <div
                        id="attendanceMessage"
                        class="mt-3 text-sm text-gray-600">
                    </div>

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
               Shift Updates
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

                        @if($nextShift->shift->dress_code)
                            <div class="bg-amber-50 border border-amber-100 rounded-xl p-3 mt-3">
                                <p class="text-xs font-semibold text-amber-800 mb-1">
                                    <i class="fas fa-tshirt mr-1"></i> Dress Code
                                </p>

                                <p class="text-sm text-gray-700">
                                    {{ $nextShift->shift->dress_code }}
                                </p>
                            </div>
                        @endif

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


    <!-- PAGINATION -->
    <!-- FILTERS + SEARCH -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">

        <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-5">

            <!-- STATUS TABS -->
            <div class="flex flex-wrap gap-3">

                <a href="{{ route('staff.shifts.index') }}"
                class="px-4 py-2 rounded-xl text-sm font-medium transition
                {{ request('status') == null ? 'bg-black text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    All
                </a>

                <a href="{{ route('staff.shifts.index', ['status' => 'Assigned']) }}"
                class="px-4 py-2 rounded-xl text-sm font-medium transition
                {{ request('status') == 'Assigned' ? 'bg-yellow-500 text-white' : 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200' }}">
                    Today
                </a>

                <a href="{{ route('staff.shifts.index', ['status' => 'Accepted']) }}"
                class="px-4 py-2 rounded-xl text-sm font-medium transition
                {{ request('status') == 'Accepted' ? 'bg-green-600 text-white' : 'bg-green-100 text-green-700 hover:bg-green-200' }}">
                    Upcoming
                </a>

                <a href="{{ route('staff.shifts.index', ['status' => 'Declined']) }}"
                class="px-4 py-2 rounded-xl text-sm font-medium transition
                {{ request('status') == 'Declined' ? 'bg-red-600 text-white' : 'bg-red-100 text-red-700 hover:bg-red-200' }}">
                    Pending
                </a>

                <a href="{{ route('staff.shifts.index', ['status' => 'Completed']) }}"
                class="px-4 py-2 rounded-xl text-sm font-medium transition
                {{ request('status') == 'Completed' ? 'bg-blue-600 text-white' : 'bg-blue-100 text-blue-700 hover:bg-blue-200' }}">
                    Completed
                </a>

            </div>

            <!-- SEARCH -->
            <form method="GET" action="{{ route('staff.shifts.index') }}">

                <div class="flex gap-3">

                    @if(request('status'))
                        <input type="hidden" name="status" value="{{ request('status') }}">
                    @endif

                    <input type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search title, location or date..."
                        class="w-full xl:w-80 border border-gray-200 rounded-xl px-4 py-2 focus:ring-2 focus:ring-black focus:outline-none">

                    <button type="submit"
                        class="bg-black hover:bg-gray-800 text-white px-5 py-2 rounded-xl text-sm font-medium transition">
                        Search
                    </button>

                </div>

            </form>

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
        <p>{{$recentShifts->links()}}</p>
    </div>

</div>

</div>

<!-- AVAILABLE SHIFT DETAILS MODAL -->
<div id="availableShiftModal"
     class="fixed inset-0 z-[10000] hidden items-center justify-center bg-black/50 p-4">

    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">

        <div class="flex items-center justify-between p-6 border-b">

            <div>
                <h3 class="text-xl font-bold text-gray-900" id="modalShiftTitle">
                    Shift Details
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    Review the shift information before accepting.
                </p>
            </div>

            <button type="button"
                    id="closeAvailableShiftModal"
                    class="w-9 h-9 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-600">
                <i class="fas fa-times"></i>
            </button>

        </div>

        <div class="p-6 space-y-5">

            <div class="grid sm:grid-cols-2 gap-4">

                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-500">Shift Date</p>
                    <p class="font-semibold mt-1" id="modalShiftDate">-</p>
                </div>

                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-500">Working Hours</p>
                    <p class="font-semibold mt-1" id="modalShiftTime">-</p>
                </div>

                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-500">Location</p>
                    <p class="font-semibold mt-1" id="modalShiftLocation">-</p>
                </div>

                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-500">Required Role</p>
                    <p class="font-semibold mt-1" id="modalShiftRole">-</p>
                </div>

                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-500">Supervisor</p>
                    <p class="font-semibold mt-1" id="modalShiftSupervisor">-</p>
                </div>

                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-500">Available Positions</p>
                    <p class="font-semibold mt-1" id="modalShiftRemaining">-</p>
                </div>

            </div>

            <div id="modalDescriptionWrapper" class="hidden">
                <h4 class="font-semibold text-gray-900 mb-2">Description</h4>
                <p class="text-sm text-gray-600 whitespace-pre-line" id="modalShiftDescription"></p>
            </div>

            <div id="modalDressCodeWrapper" class="hidden bg-amber-50 border border-amber-100 rounded-xl p-4">
                <h4 class="font-semibold text-amber-800 mb-2">
                    <i class="fas fa-tshirt mr-2"></i> Dress Code
                </h4>
                <p class="text-sm text-gray-700 whitespace-pre-line" id="modalShiftDressCode"></p>
            </div>

            <div id="modalInstructionsWrapper" class="hidden">
                <h4 class="font-semibold text-gray-900 mb-2">Instructions</h4>
                <p class="text-sm text-gray-600 whitespace-pre-line" id="modalShiftInstructions"></p>
            </div>

            <div id="modalNotesWrapper" class="hidden">
                <h4 class="font-semibold text-gray-900 mb-2">Additional Notes</h4>
                <p class="text-sm text-gray-600 whitespace-pre-line" id="modalShiftNotes"></p>
            </div>

            <div id="availableShiftMessage" class="hidden text-sm rounded-xl p-3"></div>

        </div>

        <div class="flex flex-col sm:flex-row justify-end gap-3 p-6 border-t bg-gray-50">

            <button type="button"
                    id="cancelAvailableShiftModal"
                    class="px-5 py-3 rounded-xl border border-gray-200 text-gray-700 font-semibold hover:bg-gray-100">
                Close
            </button>

            <button type="button"
                    id="acceptAvailableShiftBtn"
                    class="px-6 py-3 rounded-xl bg-black text-white font-semibold hover:bg-gray-800 disabled:opacity-50">
                <i class="fas fa-check mr-2"></i>
                Accept Shift
            </button>

        </div>

    </div>

</div>

<script>
const attendanceBtn = document.getElementById('attendanceBtn');

if(attendanceBtn){

    attendanceBtn.addEventListener('click', function(){

        if(this.dataset.action === 'checkin'){

            checkIn();

        }else if(this.dataset.action === 'checkout'){

            checkOut();

        }

    });

}

/*
|--------------------------------------------------------------------------
| UI Helpers
|--------------------------------------------------------------------------
*/

function setButtonLoading(text) {

    attendanceBtn.disabled = true;

    attendanceBtn.querySelector('.btn-text').innerHTML = text;

}

function resetButton(text) {

    attendanceBtn.disabled = false;

    attendanceBtn.querySelector('.btn-text').innerHTML = text;

}

function showMessage(message, type = 'success') {

    attendanceMessage.innerHTML = message;

    attendanceMessage.className = "mt-3 text-sm";

    switch (type) {

        case 'success':
            attendanceMessage.classList.add('text-green-600');
            break;

        case 'error':
            attendanceMessage.classList.add('text-red-600');
            break;

        case 'warning':
            attendanceMessage.classList.add('text-yellow-600');
            break;

        default:
            attendanceMessage.classList.add('text-gray-600');

    }

}

/*
|--------------------------------------------------------------------------
| GPS Helper
|--------------------------------------------------------------------------
*/

function getCurrentLocation() {

    return new Promise((resolve, reject) => {

        if (!navigator.geolocation) {

            reject("Your browser doesn't support GPS.");

            return;

        }

        navigator.geolocation.getCurrentPosition(

            (position) => {

                resolve({

                    latitude: position.coords.latitude,

                    longitude: position.coords.longitude

                });

            },

            (error) => {

                let message = "Unable to get your location.";

                switch (error.code) {

                    case error.PERMISSION_DENIED:
                        message = "Location permission denied.";
                        break;

                    case error.POSITION_UNAVAILABLE:
                        message = "Location unavailable.";
                        break;

                    case error.TIMEOUT:
                        message = "Location request timed out.";
                        break;

                }

                reject(message);

            },

            {
                enableHighAccuracy: true,
                timeout: 15000,
                maximumAge: 0
            }

        );

    });

}

/*
|--------------------------------------------------------------------------
| Attendance API Helper
|--------------------------------------------------------------------------
*/


async function sendAttendance(url) {
    try {
        setButtonLoading("Getting Location...");

        const location = await getCurrentLocation();
        const action = attendanceBtn.dataset.action;

        setButtonLoading(
            action === "checkin" ? "Checking In..." : "Checking Out..."
        );

        const response = await fetch(url, {
            method: "POST",
            credentials: "same-origin",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                attendance_id: attendanceBtn.dataset.attendance,
                latitude: location.latitude,
                longitude: location.longitude
            })
        });

        // Read the response as text first.
        const responseText = await response.text();

        let result;

        try {
            result = JSON.parse(responseText);
        } catch (parseError) {
            console.error("Attendance API returned a non-JSON response:", {
                status: response.status,
                url: response.url,
                response: responseText
            });

            if (response.redirected) {
                throw new Error("Your session may have expired. Please log in again.");
            }

            throw new Error(
                "The server returned an unexpected response. Please check the application logs."
            );
        }

        if (!response.ok) {
            throw new Error(
                result.message || `Request failed with status ${response.status}.`
            );
        }

        return result;

    } catch (error) {
        console.error("Attendance Error:", error);

        return {
            success: false,
            message: error.message || "Unable to process attendance. Please try again."
        };
    }
}


/*
|--------------------------------------------------------------------------
| Check In
|--------------------------------------------------------------------------
*/

async function checkIn() {

    const result = await sendAttendance(
        "{{ route('staff.attendance.checkin') }}"
    );

    if (!result.success) {

        resetButton("Check In");

        showMessage(result.message, "error");

        return;

    }

    showMessage(result.message, "success");

    attendanceBtn.disabled = true;

    attendanceBtn.querySelector(".btn-text").innerHTML = "Refreshing...";

    setTimeout(() => {

        window.location.reload();

    }, 1200);

}

/*
|--------------------------------------------------------------------------
| Check Out
|--------------------------------------------------------------------------
*/

async function checkOut() {

    const result = await sendAttendance(
        "{{ route('staff.attendance.checkout') }}"
    );

    if (!result.success) {

        resetButton("Check Out");

        showMessage(result.message, "error");

        return;

    }

    showMessage(result.message, "success");

        attendanceBtn.disabled = true;

        attendanceBtn.querySelector(".btn-text").innerHTML = "Refreshing...";

        setTimeout(() => {

            window.location.reload();

        }, 1200);

}
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const modal = document.getElementById('availableShiftModal');

    const closeButton = document.getElementById('closeAvailableShiftModal');
    const cancelButton = document.getElementById('cancelAvailableShiftModal');

    const acceptButton = document.getElementById('acceptAvailableShiftBtn');

    const messageBox = document.getElementById('availableShiftMessage');

    let selectedShiftId = null;

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    function setText(id, value) {

        const element = document.getElementById(id);

        if (element) {
            element.textContent = value || '-';
        }

    }

    function setOptionalSection(wrapperId, contentId, value) {

        const wrapper = document.getElementById(wrapperId);
        const content = document.getElementById(contentId);

        if (!value || !value.trim()) {

            wrapper.classList.add('hidden');

            return;

        }

        content.textContent = value;

        wrapper.classList.remove('hidden');

    }

    function showModalMessage(message, type = 'error') {

        messageBox.textContent = message;

        messageBox.className = 'text-sm rounded-xl p-3';

        if (type === 'success') {

            messageBox.classList.add(
                'bg-green-50',
                'text-green-700',
                'border',
                'border-green-200'
            );

        } else {

            messageBox.classList.add(
                'bg-red-50',
                'text-red-700',
                'border',
                'border-red-200'
            );

        }

    }

    function closeModal() {

        modal.classList.add('hidden');
        modal.classList.remove('flex');

        selectedShiftId = null;

        messageBox.classList.add('hidden');

    }

    /*
    |--------------------------------------------------------------------------
    | OPEN SHIFT DETAILS
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll('.viewAvailableShift').forEach(button => {

        button.addEventListener('click', function () {

            const shift = this.dataset;

            selectedShiftId = shift.id;

            setText('modalShiftTitle', shift.title);
            setText('modalShiftDate', shift.date);
            setText('modalShiftTime', `${shift.start} - ${shift.end}`);
            setText('modalShiftLocation', shift.location);
            setText('modalShiftRole', shift.role);
            setText('modalShiftSupervisor', shift.supervisor);
            setText('modalShiftRemaining', `${shift.remaining} position(s)`);

            setOptionalSection(
                'modalDescriptionWrapper',
                'modalShiftDescription',
                shift.description
            );

            setOptionalSection(
                'modalDressCodeWrapper',
                'modalShiftDressCode',
                shift.dressCode
            );

            setOptionalSection(
                'modalInstructionsWrapper',
                'modalShiftInstructions',
                shift.instructions
            );

            setOptionalSection(
                'modalNotesWrapper',
                'modalShiftNotes',
                shift.notes
            );

            messageBox.classList.add('hidden');

            acceptButton.disabled = false;

            acceptButton.innerHTML =
                '<i class="fas fa-check mr-2"></i> Accept Shift';

            modal.classList.remove('hidden');
            modal.classList.add('flex');

        });

    });

    /*
    |--------------------------------------------------------------------------
    | CLOSE MODAL
    |--------------------------------------------------------------------------
    */

    closeButton.addEventListener('click', closeModal);

    cancelButton.addEventListener('click', closeModal);

    modal.addEventListener('click', function (event) {

        if (event.target === modal) {
            closeModal();
        }

    });

    /*
    |--------------------------------------------------------------------------
    | ACCEPT AVAILABLE SHIFT
    |--------------------------------------------------------------------------
    */

    acceptButton.addEventListener('click', async function () {

        if (!selectedShiftId) {
            return;
        }

        if (!confirm('Are you sure you want to accept this shift?')) {
            return;
        }

        const originalContent = this.innerHTML;

        this.disabled = true;

        this.innerHTML =
            '<i class="fas fa-spinner fa-spin mr-2"></i> Accepting...';

        messageBox.classList.add('hidden');

        try {

            const response = await fetch(
                "{{ route('staff.shifts.accept-available', ':id') }}"
                    .replace(':id', selectedShiftId),
                {
                    method: 'POST',

                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector(
                            'meta[name="csrf-token"]'
                        ).content
                    }
                }
            );

            const result = await response.json();

            if (!response.ok || !result.success) {

                showModalMessage(
                    result.message || 'Unable to accept this shift.'
                );

                this.disabled = false;
                this.innerHTML = originalContent;

                return;

            }

            showModalMessage(result.message, 'success');

            this.innerHTML =
                '<i class="fas fa-check mr-2"></i> Accepted';

            setTimeout(() => {

                window.location.reload();

            }, 1200);

        } catch (error) {

            showModalMessage(
                'Something went wrong. Please try again.'
            );

            this.disabled = false;
            this.innerHTML = originalContent;

            console.error(error);

        }

    });

});
</script>
@endsection