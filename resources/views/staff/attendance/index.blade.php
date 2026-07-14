@extends('layouts.staff')

@section('title', 'My Attendance')

@section('content')

<!-- ===============================
     PAGE HEADER
================================ -->

<div class="bg-white border-b">

    <div class="max-w-7xl mx-auto px-6 py-8">

        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">

            <div>

                <h1 class="text-3xl font-bold text-slate-800">

                    My Attendance

                </h1>

                <p class="mt-2 text-gray-500">

                    View your attendance history, check-in records and worked hours.

                </p>

            </div>

        </div>

    </div>

</div>

<!-- ===============================
     ATTENDANCE STATISTICS
================================ -->

<div class="max-w-7xl mx-auto px-6 py-8">

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">

        <!-- Present -->

        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-lg transition-all duration-300 p-6">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm text-gray-500">

                        Present

                    </p>

                    <h2 class="mt-2 text-3xl font-bold text-green-600">

                        {{ $presentCount }}

                    </h2>

                </div>

                <div class="w-14 h-14 rounded-2xl bg-green-100 flex items-center justify-center">

                    <i class="fas fa-check-circle text-2xl text-green-600"></i>

                </div>

            </div>

        </div>

        <!-- Late -->

        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-lg transition-all duration-300 p-6">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm text-gray-500">

                        Late Arrivals

                    </p>

                    <h2 class="mt-2 text-3xl font-bold text-red-600">

                        {{ $lateCount }}

                    </h2>

                </div>

                <div class="w-14 h-14 rounded-2xl bg-red-100 flex items-center justify-center">

                    <i class="fas fa-clock text-2xl text-red-600"></i>

                </div>

            </div>

        </div>

        <!-- Worked Hours -->

        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-lg transition-all duration-300 p-6">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm text-gray-500">

                        Worked Hours

                    </p>

                    <h2 class="mt-2 text-3xl font-bold text-indigo-600">

                        {{ number_format($workedHours,1) }}

                    </h2>

                </div>

                <div class="w-14 h-14 rounded-2xl bg-indigo-100 flex items-center justify-center">

                    <i class="fas fa-business-time text-2xl text-indigo-600"></i>

                </div>

            </div>

        </div>

        <!-- Attendance Rate -->

        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-lg transition-all duration-300 p-6">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm text-gray-500">

                        Attendance Rate

                    </p>

                    <h2 class="mt-2 text-3xl font-bold text-blue-600">

                        {{ $attendanceRate }}%

                    </h2>

                </div>

                <div class="w-14 h-14 rounded-2xl bg-blue-100 flex items-center justify-center">

                    <i class="fas fa-chart-line text-2xl text-blue-600"></i>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- ===============================
     ATTENDANCE HISTORY
================================ -->

<div class="max-w-7xl mx-auto px-6 pb-10">

    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">

        <!-- Card Header -->

        <div class="flex items-center justify-between px-6 py-5 border-b">

            <div>

                <h2 class="text-xl font-bold text-slate-800">

                    Attendance History

                </h2>

                <p class="text-sm text-gray-500 mt-1">

                    View all your attendance records, check-in and check-out history.

                </p>

            </div>

            <div class="hidden md:flex items-center gap-2">

                <span class="px-3 py-1 rounded-full bg-indigo-100 text-indigo-700 text-sm font-medium">

                    {{ $attendances->total() }} Records

                </span>

            </div>

        </div>

        <!-- Attendance List -->

       <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

            @forelse($attendances as $attendance)

                {{-- Attendance Card Starts Here --}}
                @php

                $badge = match($attendance->status){

                    'Pending'      => 'bg-blue-100 text-blue-700',

                    'Checked In'   => 'bg-green-100 text-green-700',

                    'Checked Out'  => 'bg-purple-100 text-purple-700',

                    'Completed'    => 'bg-purple-100 text-purple-700',

                    'Late'         => 'bg-red-100 text-red-700',

                    'Absent'       => 'bg-gray-200 text-gray-700',

                    'Early Leave'  => 'bg-orange-100 text-orange-700',

                    default        => 'bg-gray-100 text-gray-700'

                };

                $workedHours = '--';

                if($attendance->check_in_time && $attendance->check_out_time){

                    $minutes = $attendance->check_in_time
                                ->diffInMinutes($attendance->check_out_time);

                    $hours = floor($minutes / 60);

                    $mins = $minutes % 60;

                    $workedHours = "{$hours}h {$mins}m";

                }

            @endphp

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 p-6 flex flex-col">

                <div class="flex justify-between items-start gap-4">

                    <!-- Shift Information -->

                    <div class="flex gap-4">

                        <div class="w-14 h-14 rounded-2xl bg-indigo-100 flex items-center justify-center">

                            <i class="fas fa-calendar-check text-indigo-600 text-xl"></i>

                        </div>

                        <div>

                            <h3 class="text-lg font-bold text-slate-800">

                                {{ $attendance->shift->title }}

                            </h3>

                            <p class="text-sm text-gray-500 mt-1">

                                {{ \Carbon\Carbon::parse($attendance->shift->shift_date)->format('l, d M Y') }}

                            </p>

                            <div class="flex flex-wrap gap-4 mt-3 text-sm text-gray-600">

                                <span>

                                    <i class="fas fa-clock text-indigo-500 mr-1"></i>

                                    {{ \Carbon\Carbon::parse($attendance->shift->start_time)->format('g:i A') }}

                                    -

                                    {{ \Carbon\Carbon::parse($attendance->shift->end_time)->format('g:i A') }}

                                </span>

                                <span>

                                    <i class="fas fa-globe-africa text-blue-500 mr-1"></i>

                                    {{ $attendance->shift->timezone }}

                                </span>

                            </div>

                        </div>                        

                    </div>

                    <!-- Status -->

                    <div class="flex items-center">

                        <span class="px-4 py-2 rounded-full text-sm font-semibold {{ $badge }}">

                            {{ $attendance->status }}

                        </span>

                    </div>

                </div>

                <!-- Details -->

                <div class="grid grid-cols-2 gap-4 mt-6">

                    <div>

                        <p class="text-xs uppercase tracking-wide text-gray-500">

                            Check In

                        </p>

                        <p class="mt-2 font-semibold text-slate-700">

                            {{ $attendance->check_in_time
                                ? $attendance->check_in_time->format('g:i A')
                                : '--' }}

                        </p>

                    </div>

                    <div>

                        <p class="text-xs uppercase tracking-wide text-gray-500">

                            Check Out

                        </p>

                        <p class="mt-2 font-semibold text-slate-700">

                            {{ $attendance->check_out_time
                                ? $attendance->check_out_time->format('g:i A')
                                : '--' }}

                        </p>

                    </div>

                    <div>

                        <p class="text-xs uppercase tracking-wide text-gray-500">

                            Worked Hours

                        </p>

                        <p class="mt-2 font-semibold text-slate-700">

                            {{ number_format($attendance->worked_hours, 2) }} hrs

                        </p>

                    </div>

                    <div>

                        <p class="text-xs uppercase tracking-wide text-gray-500">

                            Supervisor

                        </p>

                        <p class="mt-2 font-semibold text-slate-700">

                            {{ optional($attendance->shift->supervisor)->name ?? 'Administrator' }}

                        </p>

                    </div>                    

                </div>

                @if($attendance->remarks)

                        <div class="mt-5 rounded-2xl border border-amber-200 bg-amber-50 p-4">

                            <div class="flex items-start gap-3">

                                <div class="text-2xl">
                                    🛠
                                </div>

                                <div class="flex-1">

                                    <h4 class="font-semibold text-amber-900">
                                        Attendance Resolution
                                    </h4>

                                    <p class="text-sm text-amber-800 mt-1">
                                        This attendance was adjusted by an administrator or supervisor.
                                    </p>

                                    <div class="mt-4 space-y-3">

                                        <div>

                                            <p class="text-xs uppercase tracking-wide text-amber-700">
                                                Reason
                                            </p>

                                            <p class="text-sm text-slate-700">
                                                {{ $attendance->remarks }}
                                            </p>

                                        </div>

                                        @if($attendance->resolved_at)

                                        <div>

                                            <p class="text-xs uppercase tracking-wide text-amber-700">
                                                Resolved On
                                            </p>

                                            <p class="text-sm text-slate-700">
                                                {{ $attendance->resolved_at->format('d M Y h:i A') }}
                                            </p>

                                        </div>

                                        @endif

                                        @if($attendance->resolver)

                                        <div>

                                            <p class="text-xs uppercase tracking-wide text-amber-700">
                                                Resolved By
                                            </p>

                                            <p class="text-sm text-slate-700">
                                                {{ $attendance->resolver->name }}
                                            </p>

                                        </div>

                                        @endif

                                    </div>

                                </div>

                            </div>

                        </div>

                        @endif

                <!-- Footer -->

                <!-- <div class="mt-8 flex justify-end">

                    <button

                        onclick="openAttendanceInspector({{ $attendance->id }})"

                        class="inline-flex items-center gap-2 px-5 py-3 rounded-xl border border-gray-300 hover:bg-slate-100 transition font-medium">

                        <i class="fas fa-eye"></i>

                        View Details

                    </button>

                </div> -->

            </div>

            @empty

                <div class="py-20 text-center">

                    <div class="w-20 h-20 mx-auto rounded-full bg-gray-100 flex items-center justify-center">

                        <i class="fas fa-calendar-times text-4xl text-gray-400"></i>

                    </div>

                    <h3 class="mt-6 text-2xl font-bold text-gray-700">

                        No Attendance Records

                    </h3>

                    <p class="mt-2 text-gray-500">

                        Your attendance history will appear here once you start working shifts.

                    </p>

                </div>

            @endforelse

        </div>

        @if($attendances->hasPages())

            <div class="px-6 py-5 border-t bg-gray-50">

                {{ $attendances->links() }}

            </div>

        @endif

    </div>

</div>



<!-- =======================================================
    ATTENDANCE INSPECTOR
======================================================== -->

<div
    id="attendanceInspector"
    class="fixed inset-0 z-50 hidden">

    <!-- Backdrop -->

    <div
        id="attendanceOverlay"
        class="absolute inset-0 bg-black/40 backdrop-blur-sm">

    </div>

    <!-- Panel -->

    <div
        id="attendancePanel"
        class="absolute right-0 top-0 h-full w-full md:w-[520px] bg-white shadow-2xl translate-x-full transition-transform duration-300 flex flex-col">

        <!-- Header -->

        <div class="border-b px-6 py-5 flex items-center justify-between">

            <div>

                <h2 class="text-2xl font-bold text-slate-800">

                    Attendance Details

                </h2>

                <p class="text-gray-500 text-sm mt-1">

                    Shift attendance information

                </p>

            </div>

            <button
                id="closeAttendanceInspector"
                class="w-10 h-10 rounded-xl hover:bg-gray-100 transition">

                <i class="fas fa-times text-lg"></i>

            </button>

        </div>

        <!-- Body -->

        <div
            id="attendanceInspectorBody"
            class="flex-1 overflow-y-auto p-6">

            <div class="flex justify-center py-20">

                <div class="text-center">

                    <i class="fas fa-spinner fa-spin text-4xl text-indigo-500"></i>

                    <p class="mt-4 text-gray-500">

                        Loading attendance...

                    </p>

                </div>

            </div>

        </div>

    </div>

</div>



@endsection

<script>    

const inspector = document.getElementById('attendanceInspector');

const panel = document.getElementById('attendancePanel');

const overlay = document.getElementById('attendanceOverlay');

const body = document.getElementById('attendanceInspectorBody');

const closeBtn = document.getElementById('closeAttendanceInspector');


/*
|--------------------------------------------------------------------------
| Open Inspector
|--------------------------------------------------------------------------
*/

function openAttendanceInspector(id)
{

    inspector.classList.remove('hidden');

    setTimeout(() => {

        panel.classList.remove('translate-x-full');

    },100);


    body.innerHTML = `

        <div class="flex justify-center py-20">

            <div class="text-center">

                <i class="fas fa-spinner fa-spin text-4xl text-indigo-600"></i>

                <p class="mt-4 text-gray-500">

                    Loading attendance...

                </p>

            </div>

        </div>

    `;


    fetch(`/staff/attendance/${id}`)

        .then(res => res.json())

        .then(attendance => {

            renderAttendance(attendance);

        });

}


/*
|--------------------------------------------------------------------------
| Close Inspector
|--------------------------------------------------------------------------
*/

function closeAttendanceInspector()
{

    panel.classList.add('translate-x-full');

    setTimeout(() => {

        inspector.classList.add('hidden');

    },300);

}


closeBtn.onclick = closeAttendanceInspector;

overlay.onclick = closeAttendanceInspector;


document.addEventListener('keydown',function(e){

    if(e.key==="Escape"){

        closeAttendanceInspector();

    }

});


/*
|--------------------------------------------------------------------------
| Render Attendance
|--------------------------------------------------------------------------
*/

function renderAttendance(attendance)
{

    let badge = "bg-gray-100 text-gray-700";

    switch(attendance.status){

        case "Pending":

            badge="bg-blue-100 text-blue-700";

            break;

        case "Checked In":

            badge="bg-green-100 text-green-700";

            break;

        case "Checked Out":

        case "Completed":

            badge="bg-purple-100 text-purple-700";

            break;

        case "Late":

            badge="bg-red-100 text-red-700";

            break;

        case "Absent":

            badge="bg-gray-200 text-gray-700";

            break;

        case "Early Leave":

            badge="bg-orange-100 text-orange-700";

            break;

    }

    body.innerHTML = '';

    body.innerHTML += `
        <div class="space-y-6">

        <div>

            <h2 class="text-2xl font-bold text-slate-800">

                ${attendance.shift.title}

            </h2>

            <span class="inline-block mt-3 px-4 py-2 rounded-full text-sm font-semibold ${badge}">

                ${attendance.status}

            </span>

        </div>


        <div class="grid grid-cols-2 gap-5">

            <div>

                <p class="text-xs uppercase text-gray-500">

                    Shift Date

                </p>

                <p class="mt-1 font-semibold">

                    ${attendance.shift.shift_date}

                </p>

            </div>


            <div>

                <p class="text-xs uppercase text-gray-500">

                    Time

                </p>

                <p class="mt-1 font-semibold">

                    ${attendance.shift.start_time}

                    -

                    ${attendance.shift.end_time}

                </p>

            </div>


            <div>

                <p class="text-xs uppercase text-gray-500">

                    Timezone

                </p>

                <p class="mt-1 font-semibold">

                    ${attendance.shift.timezone}

                </p>

            </div>


            <div>

                <p class="text-xs uppercase text-gray-500">

                    Supervisor

                </p>

                <p class="mt-1 font-semibold">

                    ${attendance.shift.supervisor
                        ? attendance.shift.supervisor.name
                        : 'Administrator'}

                </p>

            </div>

        </div>
                <div class="border-t pt-6">

            <h3 class="font-semibold text-lg mb-4">

                Attendance

            </h3>

            <div class="grid grid-cols-2 gap-5">

                <div>

                    <p class="text-xs uppercase text-gray-500">

                        Check In

                    </p>

                    <p class="mt-1 font-semibold">

                        ${attendance.check_in_time ?? '--'}

                    </p>

                </div>


                <div>

                    <p class="text-xs uppercase text-gray-500">

                        Check Out

                    </p>

                    <p class="mt-1 font-semibold">

                        ${attendance.check_out_time ?? '--'}

                    </p>

                </div>


                <div>

                    <p class="text-xs uppercase text-gray-500">

                        Late Minutes

                    </p>

                    <p class="mt-1 font-semibold">

                        ${attendance.late_minutes ?? 0}

                    </p>

                </div>


                <div>

                    <p class="text-xs uppercase text-gray-500">

                        Early Leave

                    </p>

                    <p class="mt-1 font-semibold">

                        ${attendance.early_leave_minutes ?? 0}

                    </p>

                </div>

            </div>

        </div>
                <div class="border-t pt-6">

            <h3 class="font-semibold text-lg mb-4">

                GPS Coordinates

            </h3>

            <div class="space-y-4">

                <div>

                    <p class="text-xs uppercase text-gray-500">

                        Check In

                    </p>

                    <p class="mt-1 font-semibold">

                        ${attendance.check_in_lat ?? '--'}

                        ,

                        ${attendance.check_in_lng ?? '--'}

                    </p>

                </div>

                <div>

                    <p class="text-xs uppercase text-gray-500">

                        Check Out

                    </p>

                    <p class="mt-1 font-semibold">

                        ${attendance.check_out_lat ?? '--'}

                        ,

                        ${attendance.check_out_lng ?? '--'}

                    </p>

                </div>

            </div>

        </div>


        <div class="border-t pt-6">

            <p class="text-xs uppercase text-gray-500">

                Remarks

            </p>

            <p class="mt-2 text-gray-700">

                ${attendance.remarks ?? 'No remarks available.'}

            </p>

        </div>

    </div>

    `;

}

</script>

