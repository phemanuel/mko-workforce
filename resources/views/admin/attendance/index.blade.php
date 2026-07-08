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
        `${att.shift?.start_time ?? '--'} - ${att.shift?.end_time ?? '--'}`;

    document.getElementById('inspectorCheckIn').textContent =
        att.check_in_time ?? 'Not Checked In';

    document.getElementById('inspectorCheckOut').textContent =
        att.check_out_time ?? 'Not Checked Out';

    document.getElementById('inspectorHours').textContent =
        att.worked_hours ?? '--';

    document.getElementById('checkInLocation').textContent =
        (att.check_in_lat && att.check_in_lng)
            ? `${att.check_in_lat}, ${att.check_in_lng}`
            : '--';

    document.getElementById('checkOutLocation').textContent =
        (att.check_out_lat && att.check_out_lng)
            ? `${att.check_out_lat}, ${att.check_out_lng}`
            : '--';

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

        case 'Completed':
            badge.classList.add('bg-green-600');
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
                    ${att.created_at}
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
                    ${att.check_in_time}
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
document.querySelectorAll('.attendance-toggle').forEach(button => {

    button.addEventListener('click', function () {

        const content = this.nextElementSibling;

        const icon = this.querySelector('i');

        content.classList.toggle('hidden');

        icon.classList.toggle('rotate-180');

    });

});
</script>
@endsection