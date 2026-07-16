@extends('layouts.admin')

@section('title', 'Attendance Management')

@section('content')

<div class="space-y-6">

    <!-- PAGE HEADER -->
    <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 rounded-3xl overflow-hidden shadow-lg">

        <div class="px-8 py-8 flex flex-col lg:flex-row lg:items-center lg:justify-between">

            <div>

                <h1 class="text-3xl font-bold text-white">
                    Attendance Management
                </h1>

                <p class="text-slate-300 mt-2">
                    Monitor employee attendance, check-ins and shift progress in real time.
                </p>

            </div>

            <div class="mt-6 lg:mt-0">

                <button
                    onclick="loadAttendance()"
                    class="bg-white text-slate-900 font-semibold px-6 py-3 rounded-xl shadow hover:bg-slate-100 transition">

                    ↻ Refresh Attendance

                </button>

            </div>

        </div>

    </div>


    <!-- KPI CARDS -->

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-5">

        <div class="bg-white rounded-3xl shadow-sm border p-5">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm text-gray-500">
                        Pending
                    </p>

                    <h2 id="assignedCount"
                        class="text-3xl font-bold mt-2">
                        {{ $pendingCount ?? 0 }}
                    </h2>

                </div>

                <div class="w-14 h-14 rounded-2xl bg-blue-100 flex items-center justify-center text-2xl">
                    👥
                </div>

            </div>

        </div>


        <div class="bg-white rounded-3xl shadow-sm border p-5">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm text-gray-500">
                        Checked In
                    </p>

                    <h2 id="checkedInCount"
                        class="text-3xl font-bold mt-2 text-green-600">

                        {{ $checkedInCount ?? 0 }}

                    </h2>

                </div>

                <div class="w-14 h-14 rounded-2xl bg-green-100 flex items-center justify-center text-2xl">
                    ✅
                </div>

            </div>

        </div>


        <div class="bg-white rounded-3xl shadow-sm border p-5">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm text-gray-500">
                        Completed
                    </p>

                    <h2 id="completedCount"
                        class="text-3xl font-bold mt-2 text-purple-600">

                        {{ $completedCount ?? 0 }}

                    </h2>

                </div>

                <div class="w-14 h-14 rounded-2xl bg-purple-100 flex items-center justify-center text-2xl">
                    🏁
                </div>

            </div>

        </div>


        <div class="bg-white rounded-3xl shadow-sm border p-5">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm text-gray-500">
                        Late
                    </p>

                    <h2 id="lateCount"
                        class="text-3xl font-bold mt-2 text-red-600">

                        {{ $lateCount ?? 0 }}

                    </h2>

                </div>

                <div class="w-14 h-14 rounded-2xl bg-red-100 flex items-center justify-center text-2xl">
                    ⏰
                </div>

            </div>

        </div>


        <div class="bg-white rounded-3xl shadow-sm border p-5">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm text-gray-500">
                        Absent
                    </p>

                    <h2 id="absentCount"
                        class="text-3xl font-bold mt-2 text-orange-600">

                        {{ $absentCount ?? 0 }}

                    </h2>

                </div>

                <div class="w-14 h-14 rounded-2xl bg-orange-100 flex items-center justify-center text-2xl">
                    ❌
                </div>

            </div>

        </div>

    </div>



    <!-- SEARCH / FILTERS -->

    <div class="bg-white rounded-3xl shadow-sm border overflow-hidden">

        <div class="px-6 py-5 border-b bg-gray-50 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

            <div class="flex-1 max-w-xl">

                <div class="relative">

                    <span class="absolute left-4 top-3.5 text-gray-400">
                        🔍
                    </span>

                    <input
                        id="attendanceSearch"
                        type="text"
                        placeholder="Search employee, shift or location..."

                        class="w-full border rounded-2xl py-3 pl-11 pr-4
                        focus:ring-2 focus:ring-black/10">

                </div>

            </div>

            <div>

                <span
                    id="attendanceTotal"
                    class="bg-black text-white px-5 py-2 rounded-xl text-sm">

                    {{ $attendances->count() }} Records

                </span>

            </div>

        </div>



        <!-- STATUS TABS -->

        <div class="px-6 pt-5">

            <div class="flex flex-wrap gap-3">

                <button
                    class="attendance-tab active px-5 py-2 rounded-full bg-black text-white"
                    data-status="">

                    All

                </button>

                <button
                    class="attendance-tab px-5 py-2 rounded-full bg-gray-100"
                    data-status="Pending">

                    Pending

                </button>

                <button
                    class="attendance-tab px-5 py-2 rounded-full bg-gray-100"
                    data-status="Checked In">

                    Checked In

                </button>

                <button
                    class="attendance-tab px-5 py-2 rounded-full bg-gray-100"
                    data-status="Completed">

                    Completed

                </button>

                <button
                    class="attendance-tab px-5 py-2 rounded-full bg-gray-100"
                    data-status="Late">

                    Late

                </button>

                <button
                    class="attendance-tab px-5 py-2 rounded-full bg-gray-100"
                    data-status="Absent">

                    Absent

                </button>

            </div>

        </div>



        <!-- GRID -->

        <div
            id="attendanceGrid"
            class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 p-6">

            @forelse($attendances as $employeeAttendances)

    @php

        $employee = $employeeAttendances->first()->employee;

        $totalShifts = $employeeAttendances->count();

        $completed = $employeeAttendances
                        ->whereIn('status',['Checked Out','Completed'])
                        ->count();

        $checkedIn = $employeeAttendances
                        ->where('status','Checked In')
                        ->count();

        $late = $employeeAttendances
                    ->where('status','Late')
                    ->count();

    @endphp

    <div class="bg-white rounded-3xl border shadow-sm overflow-hidden">

        <!-- Employee Header -->

        <button
            type="button"
            class="w-full p-6 flex justify-between items-center hover:bg-gray-50 attendance-toggle">

            <div class="text-left">

                <h3 class="text-lg font-bold text-slate-800">

                    {{ $employee->user->name }}

                </h3>

                <div class="flex gap-5 mt-2 text-sm text-gray-500">

                    <span>{{ $totalShifts }} Shift(s)</span>

                    <span class="text-green-600">
                        {{ $completed }} Completed
                    </span>

                    <span class="text-blue-600">
                        {{ $checkedIn }} Working
                    </span>

                    <span class="text-red-600">
                        {{ $late }} Late
                    </span>

                </div>

            </div>

            <i class="fas fa-chevron-down transition-transform duration-300"></i>

        </button>

        <!-- Employee Shifts -->

        <div class="attendance-content hidden border-t">

            @foreach($employeeAttendances as $attendance)

                            @php

                                $badge = match($attendance->status){

                                    'Pending' => 'bg-blue-100 text-blue-700',

                                    'Checked In' => 'bg-green-100 text-green-700',

                                    'Checked Out' => 'bg-purple-100 text-purple-700',

                                    'Completed' => 'bg-purple-100 text-purple-700',

                                    'Late' => 'bg-red-100 text-red-700',

                                    'Absent' => 'bg-gray-200 text-gray-700',

                                    default => 'bg-gray-100 text-gray-700'

                                };

                            @endphp

                            <div
                                class="flex items-center justify-between p-5 hover:bg-slate-50 border-b">

                                <div>

                                    <h4 class="font-semibold">

                                        {{ $attendance->shift->title }}

                                    </h4>

                                    <p class="text-sm text-gray-500 mt-1">

                                        {{ \Carbon\Carbon::parse($attendance->shift->shift_date)->format('D, d M Y') }}

                                        •

                                        {{ \Carbon\Carbon::parse($attendance->shift->start_time)->format('g:i A') }}

                                        -

                                        {{ \Carbon\Carbon::parse($attendance->shift->end_time)->format('g:i A') }}

                                    </p>

                                </div>

                                <div class="flex items-center gap-4">

                                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $badge }}">

                                        {{ $attendance->status }}

                                    </span>

                                    <button
                                        onclick="openAttendanceInspector({{ $attendance->id }})"
                                        class="px-4 py-2 rounded-xl border hover:bg-slate-100">

                                        View

                                    </button>

                                </div>

                            </div>

                        @endforeach

                    </div>

                </div>

            @empty

                <div class="col-span-full py-20 text-center">

                    <div class="text-6xl">

                        📋

                    </div>

                    <h3 class="text-xl font-bold mt-4">

                        No Attendance Records

                    </h3>

                    <p class="text-gray-500 mt-2">

                        Attendance will appear once staff are assigned to shifts.

                    </p>

                </div>

            @endforelse

        </div>

    </div>

</div>


@include('admin.attendance.inspector')

<!-- ==========================================================
    ADJUST ATTENDANCE MODAL
========================================================== -->

<div id="adjustAttendanceModal"
     class="fixed inset-0 z-[10000] hidden">

    <!-- Overlay -->
    <div id="adjustAttendanceModalOverlay"
         class="absolute inset-0 bg-black/40 backdrop-blur-sm">
    </div>

    <!-- Modal Wrapper -->
    <div class="relative h-screen flex items-center justify-center p-4">

        <!-- Modal -->
        <div class="w-full max-w-5xl bg-white rounded-3xl shadow-2xl
                    h-[90vh] flex flex-col overflow-hidden">

             <!-- Header -->
            <div class="flex items-center justify-between border-b px-6 py-5 shrink-0">

                <div>

                    <h2 class="text-2xl font-bold text-slate-800">

                        Adjust Attendance

                    </h2>

                    <p class="text-sm text-slate-500 mt-1">

                        Correct attendance information.

                    </p>

                </div>

                <button
                    id="closeAdjustAttendanceModal"
                    class="w-10 h-10 rounded-xl hover:bg-slate-100">

                    ✕

                </button>

            </div>

            <!-- Body -->

            <form
            id="adjustAttendanceForm"
            class="flex-1 overflow-y-auto p-6 space-y-6">

                @csrf

                <input type="hidden" id="adjustAttendanceId">

                <!-- ================================================= -->
                <!-- EMPLOYEE INFORMATION -->
                <!-- ================================================= -->

                <div class="w-full max-w-3xl bg-white rounded-3xl shadow-2xl flex flex-col max-h-[90vh]">

                    <h3 class="text-lg font-semibold mb-5">

                        Employee Information

                    </h3>

                    <div class="grid md:grid-cols-2 gap-5">

                        <div>
                            <label class="text-sm text-slate-500">Employee</label>

                            <input
                                id="adjustEmployee"
                                readonly
                                class="w-full mt-2 rounded-xl border bg-slate-100 px-4 py-3">
                        </div>

                        <div>
                            <label class="text-sm text-slate-500">Role</label>

                            <input
                                id="adjustRole"
                                readonly
                                class="w-full mt-2 rounded-xl border bg-slate-100 px-4 py-3">
                        </div>

                    </div>

                </div>


                <!-- ================================================= -->
                <!-- SHIFT INFORMATION -->
                <!-- ================================================= -->

                <div class="bg-slate-50 border rounded-2xl p-5">

                    <h3 class="text-lg font-semibold mb-5">

                        Shift Information

                    </h3>

                    <div class="grid md:grid-cols-2 gap-5">

                        <div>
                            <label class="text-sm text-slate-500">Shift</label>
                            <input id="adjustShift" readonly class="w-full mt-2 rounded-xl border bg-slate-100 px-4 py-3">
                        </div>

                        <div>
                            <label class="text-sm text-slate-500">Shift Date</label>
                            <input id="adjustShiftDate" readonly class="w-full mt-2 rounded-xl border bg-slate-100 px-4 py-3">
                        </div>

                        <div>
                            <label class="text-sm text-slate-500">Scheduled Start</label>
                            <input id="adjustShiftStart" readonly class="w-full mt-2 rounded-xl border bg-slate-100 px-4 py-3">
                        </div>

                        <div>
                            <label class="text-sm text-slate-500">Scheduled End</label>
                            <input id="adjustShiftEnd" readonly class="w-full mt-2 rounded-xl border bg-slate-100 px-4 py-3">
                        </div>

                        <div>
                            <label class="text-sm text-slate-500">Check-in Opens</label>
                            <input id="adjustCheckInOpen" readonly class="w-full mt-2 rounded-xl border bg-slate-100 px-4 py-3">
                        </div>

                        <div>
                            <label class="text-sm text-slate-500">Late After</label>
                            <input id="adjustLateAfter" readonly class="w-full mt-2 rounded-xl border bg-slate-100 px-4 py-3">
                        </div>

                        <div>
                            <label class="text-sm text-slate-500">Location</label>
                            <input id="adjustLocation" readonly class="w-full mt-2 rounded-xl border bg-slate-100 px-4 py-3">
                        </div>

                        <div>
                            <label class="text-sm text-slate-500">Timezone</label>
                            <input id="adjustTimezone" readonly class="w-full mt-2 rounded-xl border bg-slate-100 px-4 py-3">
                        </div>

                    </div>

                </div>


                <!-- ================================================= -->
                <!-- ATTENDANCE ADJUSTMENT -->
                <!-- ================================================= -->

                <div class="bg-white border rounded-2xl p-5">

                    <h3 class="text-lg font-semibold mb-5">

                        Attendance Adjustment

                    </h3>

                    <div class="grid md:grid-cols-2 gap-5">

                        <div>

                            <label class="text-sm text-slate-500">

                                Check In

                            </label>

                            <input
                                type="datetime-local"
                                id="adjustCheckIn"
                                class="w-full mt-2 rounded-xl border px-4 py-3">

                        </div>

                        <div>

                            <label class="text-sm text-slate-500">

                                Check Out

                            </label>

                            <input
                                type="datetime-local"
                                id="adjustCheckOut"
                                class="w-full mt-2 rounded-xl border px-4 py-3">

                        </div>

                    </div>

                    <div class="mt-5">

                        <label class="text-sm text-slate-500">

                            Reason

                        </label>

                        <textarea
                            id="adjustReason"
                            rows="4"
                            class="w-full mt-2 rounded-xl border px-4 py-3"
                            placeholder="Reason for adjusting attendance"></textarea>

                    </div>

                </div>

            </form>

            <!-- Footer -->

            <div class="border-t bg-white px-6 py-5 flex justify-end gap-3 shrink-0">

                <button
                    type="button"
                    id="cancelAdjustAttendanceModal"
                    class="px-5 py-3 border rounded-xl">

                    Cancel

                </button>

                <button
                    type="submit"
                    form="adjustAttendanceForm"
                    class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 rounded-xl text-white font-semibold">

                    Save Changes

                </button>

            </div>

        </div>

    </div>

</div>


<script>
    /*
|--------------------------------------------------------------------------
| Admin GPS Coordinates
|--------------------------------------------------------------------------
*/

let adminLat = null;
let adminLng = null;

function getAdminLocation()
{
    if (!navigator.geolocation) {
        return;
    }

    navigator.geolocation.getCurrentPosition(

        function(position) {

            adminLat = position.coords.latitude;
            adminLng = position.coords.longitude;

        },

        function(error) {

            console.warn('Unable to retrieve location:', error.message);

        },

        {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 0
        }

    );
}
</script>


<script>
function format12Hour(time) {
        if (!time) return '--';

        const [hours, minutes] = time.split(':');
        const date = new Date();
        date.setHours(hours, minutes);

        return date.toLocaleTimeString([], {
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
        });
    }

    function formatDateTime12Hour(dateTime) {
    if (!dateTime) return '--';

    const date = new Date(dateTime.replace(' ', 'T'));

        return date.toLocaleString([], {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: 'numeric',
            minute: '2-digit',
            second: '2-digit',
            hour12: true
        });
    }
</script>

<script>
/*
|--------------------------------------------------------------------------
| OPEN INSPECTOR
|--------------------------------------------------------------------------
*/
async function openAttendanceInspector(id)
{
    try {

        const response = await fetch(`/admin/attendance/${id}`, {
            headers: {
                Accept: 'application/json'
            }
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Unable to load attendance.');
        }

        populateAttendanceInspector(data);

        document
            .getElementById('attendanceInspector')
            .classList.remove('translate-x-full');

        document
            .getElementById('attendanceInspectorBackdrop')
            .classList.remove('hidden');

    } catch (err) {

        console.error(err);
        showToast(err.message, 'error');

    }
}



/*
|--------------------------------------------------------------------------
| CLOSE INSPECTOR
|--------------------------------------------------------------------------
*/
function closeAttendanceInspector()
{
    document
        .getElementById('attendanceInspector')
        .classList.add('translate-x-full');

    document
        .getElementById('attendanceInspectorBackdrop')
        .classList.add('hidden');
}

/*
|--------------------------------------------------------------------------
| POPULATE INSPECTOR
|--------------------------------------------------------------------------
*/
function populateAttendanceInspector(att)
{   
    

    document.getElementById('inspectorEmployee').textContent =
        att.employee?.user?.name ?? 'Unknown Employee';

    document.getElementById('inspectorRole').textContent =
        att.employee?.primary_role ?? '-';

    document.getElementById('inspectorShift').textContent =
        att.shift?.title ?? '-';

    document.getElementById('inspectorDate').textContent =
        att.shift?.shift_date ?? '-';

    document.getElementById('inspectorTime').textContent =
    `${format12Hour(att.shift?.start_time)} - ${format12Hour(att.shift?.end_time)}`;

    document.getElementById('inspectorCheckIn').textContent =
    att.check_in_time
        ? formatDateTime12Hour(att.check_in_time)
        : 'Not Checked In';

    document.getElementById('inspectorCheckOut').textContent =
        att.check_out_time
            ? formatDateTime12Hour(att.check_out_time)
            : 'Not Checked Out';

    document.getElementById('inspectorHours').textContent =
        att.worked_hours ?? '--';

    const checkInLocation = document.getElementById('checkInLocation');

    if (att.check_in_lat && att.check_in_lng) {
        checkInLocation.innerHTML = `
            <div class="space-y-1">
                <div class="text-slate-700 font-medium">
                    ${att.check_in_lat}, ${att.check_in_lng}
                </div>
                <a href="https://www.google.com/maps?q=${att.check_in_lat},${att.check_in_lng}"
                target="_blank"
                class="text-indigo-600 hover:text-indigo-700 text-sm font-medium">
                    📍 View on Google Maps
                </a>
            </div>
        `;
    } else {
        checkInLocation.textContent = '--';
    }

    const checkOutLocation = document.getElementById('checkOutLocation');

    if (att.check_out_lat && att.check_out_lng) {
        checkOutLocation.innerHTML = `
            <div class="space-y-1">
                <div class="text-slate-700 font-medium">
                    ${att.check_out_lat}, ${att.check_out_lng}
                </div>
                <a href="https://www.google.com/maps?q=${att.check_out_lat},${att.check_out_lng}"
                target="_blank"
                class="text-indigo-600 hover:text-indigo-700 text-sm font-medium">
                    📍 View on Google Maps
                </a>
            </div>
        `;
    } else {
        checkOutLocation.textContent = '--';
    }

    document.getElementById('supervisorName').textContent =
    `${att.shift.supervisor_name} (${att.shift.supervisor_role})`;

    document.getElementById('shiftNotes').textContent =
        att.shift?.notes ?? 'No notes available.';



    /*
    |--------------------------------------------------------------------------
    | STATUS BADGE
    |--------------------------------------------------------------------------
    */

    const badge = document.getElementById('inspectorStatus');

    badge.textContent = att.status;

    badge.className =
        'inline-block px-3 py-1 rounded-full text-white text-sm';

    switch(att.status){

        case 'Pending':
            badge.classList.add('bg-gray-500');
            break;

        case 'Checked In':
            badge.classList.add('bg-blue-600');
            break;

        case 'Checked Out':
            badge.classList.add('bg-green-600');
            break;

        case 'Early Leave':
            badge.classList.add('bg-yellow-500');
            break;

        case 'Late':
            badge.classList.add('bg-red-600');
            break;

        case 'Absent':
            badge.classList.add('bg-red-700');
            break;

        default:
            badge.classList.add('bg-slate-600');

    }

    /*
    |--------------------------------------------------------------------------
    | ATTENDANCE ACTIONS
    |--------------------------------------------------------------------------
    */

    const actionsCard = document.getElementById('attendanceActionsCard');
    const resolveBtn = document.getElementById('btnResolveAttendance');
    const actionMessage = document.getElementById('attendanceActionMessage');

    // Hide everything first
    actionsCard.classList.add('hidden');
    resolveBtn.classList.add('hidden');
    actionMessage.classList.add('hidden');

    // Employee checked in but has not checked out
    if (att.check_in_time && !att.check_out_time) {

        actionsCard.classList.remove('hidden');

        resolveBtn.classList.remove('hidden');

        resolveBtn.onclick = function () {

            openAdjustAttendanceModal(att);

        };

        actionMessage.classList.remove('hidden');

        actionMessage.innerHTML = `
            <div class="flex items-start gap-3">
                <div class="text-xl">⚠️</div>
                <div>
                    <div class="font-semibold">
                        Attendance Requires Resolution
                    </div>
                    <div class="text-sm mt-1">
                        This employee checked in but has not checked out.
                        Resolve the attendance before payroll is generated.
                    </div>
                </div>
            </div>
        `;
    }

    /*
    |--------------------------------------------------------------------------
    | ATTENDANCE FLAG
    |--------------------------------------------------------------------------
    */

    const flag = document.getElementById('attendanceFlag');

    if(att.late){

        flag.innerHTML =
            '<span class="text-red-600 font-semibold">Late Arrival</span>';

    }
    else if(att.early_leave){

        flag.innerHTML =
            '<span class="text-yellow-600 font-semibold">Early Leave</span>';

    }
    else{

        flag.innerHTML =
            '<span class="text-green-600 font-semibold">Normal</span>';

    }

    /*
    |--------------------------------------------------------------------------
    | RESOLUTION
    |--------------------------------------------------------------------------
    */

    const resolution = document.getElementById('attendanceResolution');

    if (att.remarks) {

        resolution.innerHTML = `
            <div class="space-y-4">

                <div class="flex justify-between">

                    <span>Resolution Status</span>

                    <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-800 text-sm font-medium">

                        Administratively Adjusted

                    </span>

                </div>

                <div class="flex justify-between">

                    <span>Resolved By</span>

                    <strong>

                        ${att.resolver
                            ? att.resolver.name
                            : 'Administrator'}

                    </strong>

                </div>

                <div class="flex justify-between">

                    <span>Resolved On</span>

                    <strong>

                        ${att.resolved_at
                            ? formatDateTime12Hour(att.resolved_at)
                            : '--'}

                    </strong>

                </div>

                <div>

                    <p class="mb-2 font-medium">

                        Reason

                    </p>

                    <div class="rounded-xl bg-amber-50 border border-amber-200 p-4 text-sm leading-6">

                        ${att.remarks}

                    </div>

                </div>

            </div>
        `;

    } else {

        resolution.innerHTML = `
            <p class="text-slate-500">

                No administrative adjustments have been made.

            </p>
        `;

    }


    /*
    |--------------------------------------------------------------------------
    | TIMELINE
    |--------------------------------------------------------------------------
    */

    let timeline = '';

    timeline += `
        <div class="flex gap-4">
            <div class="w-3 h-3 mt-2 rounded-full bg-gray-400"></div>
            <div>
                <div class="font-semibold">
                    Attendance Record Created
                </div>
                <div class="text-sm text-gray-500">
                   ${formatDateTime12Hour(att.created_at)}
                </div>
            </div>
        </div>
    `;

    if(att.check_in_time){

        timeline += `
        <div class="flex gap-4">
            <div class="w-3 h-3 mt-2 rounded-full bg-blue-500"></div>
            <div>
                <div class="font-semibold">
                    Checked In
                </div>
                <div class="text-sm text-gray-500">
                    ${formatDateTime12Hour(att.check_in_time)}
                </div>
            </div>
        </div>
        `;

    }

    if(att.check_out_time){

        timeline += `
        <div class="flex gap-4">
            <div class="w-3 h-3 mt-2 rounded-full bg-green-500"></div>
            <div>
                <div class="font-semibold">
                    Checked Out
                </div>
                <div class="text-sm text-gray-500">
                    ${att.check_out_time}
                </div>
            </div>
        </div>
        `;

    }

    document.getElementById('attendanceTimeline').innerHTML = timeline;

}



/*
|--------------------------------------------------------------------------
| SEARCH
|--------------------------------------------------------------------------
*/

document
.getElementById('attendanceSearch')
.addEventListener('keyup', function(){

    const value = this.value.toLowerCase();

    document
    .querySelectorAll('.attendance-card')
    .forEach(card=>{

        card.style.display =
            card.innerText.toLowerCase().includes(value)
            ? ''
            : 'none';

    });

});



/*
|--------------------------------------------------------------------------
| FILTERS
|--------------------------------------------------------------------------
*/

document
.querySelectorAll('.attendance-filter')
.forEach(button=>{

    button.addEventListener('click',function(){

        document
        .querySelectorAll('.attendance-filter')
        .forEach(b=>{

            b.classList.remove(
                'bg-slate-900',
                'text-white'
            );

        });

        this.classList.add(
            'bg-slate-900',
            'text-white'
        );

        const status = this.dataset.status;

        document
        .querySelectorAll('.attendance-card')
        .forEach(card=>{

            if(status==='All'){

                card.style.display='';

            }
            else{

                card.style.display =
                    card.dataset.status===status
                    ? ''
                    : 'none';

            }

        });

    });

});
</script>

<script>
    /*
|--------------------------------------------------------------------------
| ADJUST ATTENDANCE MODAL
|--------------------------------------------------------------------------
*/

let currentAttendance = null;

function convertToDateTimeLocal(dateTime)
{
    if (!dateTime) return '';

    return dateTime.replace(' ', 'T').substring(0,16);
}

function openAdjustAttendanceModal(att)
{
    currentAttendance = att;   
    
    getAdminLocation();


    document.getElementById('adjustAttendanceId').value = att.id;

    /*
    |--------------------------------------------------------------------------
    | Employee
    |--------------------------------------------------------------------------
    */

    document.getElementById('adjustEmployee').value =
        att.employee?.user?.name ?? '';

    document.getElementById('adjustRole').value =
        att.employee?.primary_role ?? '';

    /*
    |--------------------------------------------------------------------------
    | Shift
    |--------------------------------------------------------------------------
    */

    document.getElementById('adjustShift').value =
        att.shift?.title ?? '';

    document.getElementById('adjustShiftDate').value =
        att.shift?.shift_date ?? '';

    document.getElementById('adjustShiftStart').value =
        format12Hour(att.shift?.start_time);

    document.getElementById('adjustShiftEnd').value =
        format12Hour(att.shift?.end_time);

    document.getElementById('adjustCheckInOpen').value =
        (att.shift?.check_in_open_minutes ?? 0) + ' mins before start';

    document.getElementById('adjustLateAfter').value =
        (att.shift?.late_after_minutes ?? 0) + ' mins after start';

    document.getElementById('adjustLocation').value =
        att.shift?.location ?? '';

    document.getElementById('adjustTimezone').value =
        att.shift?.timezone ?? '';

    /*
    |--------------------------------------------------------------------------
    | Attendance
    |--------------------------------------------------------------------------
    */

    document.getElementById('adjustCheckIn').value =
        convertToDateTimeLocal(att.check_in_time);

    document.getElementById('adjustCheckOut').value =
        convertToDateTimeLocal(att.check_out_time);

    document.getElementById('adjustReason').value = '';

    document
        .getElementById('adjustAttendanceModal')
        .classList.remove('hidden');
}

function closeAdjustAttendanceModal()
{
    document
        .getElementById('adjustAttendanceModal')
        .classList.add('hidden');
}

document
.getElementById('closeAdjustAttendanceModal')
.addEventListener('click', closeAdjustAttendanceModal);

document
.getElementById('cancelAdjustAttendanceModal')
.addEventListener('click', closeAdjustAttendanceModal);

document
.getElementById('adjustAttendanceModalOverlay')
.addEventListener('click', closeAdjustAttendanceModal);

</script>

<script>
document.querySelectorAll('.attendance-toggle').forEach(button => {

    button.addEventListener('click', function () {

        const content = this.nextElementSibling;

        const icon = this.querySelector('i');

        content.classList.toggle('hidden');

        icon.classList.toggle('rotate-180');

    });

});
</script>

<script> const adjustAttendanceUrlTemplate = "{{ route('admin.attendance.adjust', ':id') }}"; </script>

<script>

document
.getElementById('adjustAttendanceForm')
.addEventListener('submit', function(e){

    e.preventDefault();

    const attendanceId =
        document.getElementById('adjustAttendanceId').value;

    const url =
        adjustAttendanceUrlTemplate.replace(':id', attendanceId);

    fetch(url, {

        method: 'POST',

        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN':
                document.querySelector('meta[name="csrf-token"]').content
        },

        body: JSON.stringify({

            check_in_time:
                document.getElementById('adjustCheckIn').value,

            check_out_time:
                document.getElementById('adjustCheckOut').value,

            reason:
                document.getElementById('adjustReason').value,
            
            check_out_lat: adminLat,

            check_out_lng: adminLng

        })

    })
    .then(res => res.json())
    .then(res => {

        if(res.success){

            alert('Attendance Updated');

            closeAdjustAttendanceModal();

            location.reload();

        } else {

            alert(res.message);

        }

    })
    .catch(err => {

        console.error(err);

        alert('Unable to update attendance.');

    });

});

</script>
@endsection