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

        if (action === "checkin") {

            setButtonLoading("Checking In...");

        } else {

            setButtonLoading("Checking Out...");

        }

        const response = await fetch(url, {

            method: "POST",

            headers: {

                "Content-Type": "application/json",

                "X-CSRF-TOKEN":
                    document
                        .querySelector('meta[name="csrf-token"]')
                        .content

            },

            body: JSON.stringify({

                attendance_id:
                    attendanceBtn.dataset.attendance,

                latitude:
                    location.latitude,

                longitude:
                    location.longitude

            })

        });

        return await response.json();

    } catch (error) {

        return {

            success: false,

            message: error

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
@endsection