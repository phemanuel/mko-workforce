@extends('layouts.admin')

@section('content')

<div class="space-y-6">

    <!-- HEADER -->
    <div class="flex items-center justify-between">

        <div>

            <h1 class="text-3xl font-bold text-gray-900">
                Attendance Management
            </h1>

            <p class="text-gray-500 mt-1">
                Monitor staff attendance, check-ins and shift activity.
            </p>

        </div>

    </div>


    <!-- SUMMARY CARDS -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">

        <div class="bg-white rounded-3xl border p-5 shadow-sm">

            <p class="text-sm text-gray-500">
                Pending
            </p>

            <h2 class="text-3xl font-bold mt-2 text-yellow-600">
                {{ $pendingCount }}
            </h2>

            <p class="text-xs text-gray-400 mt-2">
                Awaiting Check-in
            </p>

        </div>

        <div class="bg-white rounded-3xl border p-5 shadow-sm">

            <p class="text-sm text-gray-500">
                Checked In
            </p>

            <h2 class="text-3xl font-bold mt-2 text-blue-600">
                {{ $checkedInCount }}
            </h2>

            <p class="text-xs text-gray-400 mt-2">
                Currently Working
            </p>

        </div>

        <div class="bg-white rounded-3xl border p-5 shadow-sm">

            <p class="text-sm text-gray-500">
                Completed
            </p>

            <h2 class="text-3xl font-bold mt-2 text-green-600">
                {{ $completedCount }}
            </h2>

            <p class="text-xs text-gray-400 mt-2">
                Finished Shift
            </p>

        </div>

        <div class="bg-white rounded-3xl border p-5 shadow-sm">

            <p class="text-sm text-gray-500">
                Late
            </p>

            <h2 class="text-3xl font-bold mt-2 text-red-600">
                {{ $lateCount }}
            </h2>

            <p class="text-xs text-gray-400 mt-2">
                Need Attention
            </p>

        </div>

    </div>


    <!-- SEARCH + FILTER -->
    <div class="bg-white rounded-3xl border shadow-sm overflow-hidden">

        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 p-6 border-b bg-gray-50">

            <div class="relative w-full lg:max-w-lg">

                <span class="absolute left-4 top-3.5 text-gray-400">
                    🔍
                </span>

                <input
                    id="attendanceSearch"
                    type="text"
                    placeholder="Search employee, shift, supervisor..."
                    class="w-full pl-11 pr-4 py-3 rounded-2xl border focus:ring-2 focus:ring-black/10">

            </div>

            <div class="flex gap-2 flex-wrap">

                <button class="attendance-tab px-4 py-2 rounded-xl bg-black text-white">
                    All
                </button>

                <button class="attendance-tab px-4 py-2 rounded-xl border">
                    Pending
                </button>

                <button class="attendance-tab px-4 py-2 rounded-xl border">
                    Checked In
                </button>

                <button class="attendance-tab px-4 py-2 rounded-xl border">
                    Completed
                </button>

                <button class="attendance-tab px-4 py-2 rounded-xl border">
                    Late
                </button>

            </div>

        </div>


        <!-- TABLE -->
        <div class="overflow-x-auto">

            <table class="w-full">

                <thead class="bg-gray-50">

                    <tr class="text-left text-sm text-gray-500">

                        <th class="px-6 py-4">Employee</th>
                        <th>Shift</th>
                        <th>Supervisor</th>
                        <th>Status</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th></th>

                    </tr>

                </thead>

                <tbody id="attendanceTable">

                @forelse($attendances as $attendance)

                    @php

                        $badge = match($attendance->status){

                            'Pending' => 'bg-yellow-100 text-yellow-700',

                            'Checked In' => 'bg-blue-100 text-blue-700',

                            'Completed' => 'bg-green-100 text-green-700',

                            'Late' => 'bg-red-100 text-red-700',

                            default => 'bg-gray-100 text-gray-700'

                        };

                    @endphp

                    <tr class="border-t hover:bg-gray-50">

                        <td class="px-6 py-5">

                            <div class="font-semibold">

                                {{ $attendance->employee->user->name }}

                            </div>

                            <div class="text-xs text-gray-500">

                                {{ $attendance->employee->primary_role }}

                            </div>

                        </td>

                        <td>

                            {{ $attendance->shift->title }}

                        </td>

                        <td>

                            {{ optional($attendance->shift->supervisor)->name ?? 'Admin' }}

                        </td>

                        <td>

                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $badge }}">

                                {{ $attendance->status }}

                            </span>

                        </td>

                        <td>

                            {{ $attendance->check_in_time ?? '--' }}

                        </td>

                        <td>

                            {{ $attendance->check_out_time ?? '--' }}

                        </td>

                        <td class="pr-6">

                            <button
                                onclick="openAttendanceInspector({{ $attendance->id }})"
                                class="text-blue-600 hover:text-blue-800 font-medium">

                                View

                            </button>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="7">

                            <div class="py-20 text-center">

                                <div class="text-5xl">
                                    📋
                                </div>

                                <h3 class="text-xl font-semibold mt-5">

                                    No attendance records

                                </h3>

                                <p class="text-gray-500 mt-2">

                                    Attendance records will appear once staff are assigned to shifts.

                                </p>

                            </div>

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


@include('admin.attendance.inspector')

<script>
    async function openAttendanceInspector(id)
{
    try{

        const response = await fetch(`/admin/attendance/${id}`);

        const data = await response.json();

        const a = data.attendance;

        document.getElementById('ai_employee_name').innerText =
            a.employee.user.name;

        document.getElementById('ai_role').innerText =
            a.employee.primary_role;

        document.getElementById('ai_status').innerText =
            a.status;

        document.getElementById('ai_shift').innerText =
            a.shift.title;

        document.getElementById('ai_date').innerText =
            a.shift.shift_date;

        document.getElementById('ai_time').innerText =
            `${a.shift.start_time} - ${a.shift.end_time}`;

        document.getElementById('ai_supervisor').innerText =
            a.shift.supervisor?.name ?? 'Admin';

        document.getElementById('ai_checkin').innerText =
            a.check_in_time ?? '--';

        document.getElementById('ai_checkout').innerText =
            a.check_out_time ?? '--';

        document.getElementById('ai_hours').innerText =
            data.hours_worked;

        document.getElementById('ai_checkin_location').innerText =
            a.check_in_lat
                ? `${a.check_in_lat}, ${a.check_in_lng}`
                : '--';

        document.getElementById('ai_checkout_location').innerText =
            a.check_out_lat
                ? `${a.check_out_lat}, ${a.check_out_lng}`
                : '--';

        document.getElementById('ai_notes').value =
            a.notes ?? '';

        document.getElementById('attendanceInspector')
            .classList.remove('translate-x-full');

    }catch(e){

        console.error(e);

        alert('Unable to load attendance.');

    }
}

function closeAttendanceInspector()
{
    document.getElementById('attendanceInspector')
        .classList.add('translate-x-full');
}
</script>
@endsection