@extends('layouts.admin')

@section('content')

<!-- KPI GRID -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

    <div class="bg-white p-5 rounded-lg shadow border-l-4 border-blue-600">
        <p class="text-sm text-gray-500">Active Employees</p>
        <h2 class="text-2xl font-bold">{{ $activeEmployees }}</h2>
        <p class="text-xs text-gray-400 mt-1">Currently active staff</p>
    </div>

    <div class="bg-white p-5 rounded-lg shadow border-l-4 border-black">
        <p class="text-sm text-gray-500">Total Employees</p>
        <h2 class="text-2xl font-bold">{{ $totalEmployees }}</h2>
        <p class="text-xs text-gray-400 mt-1">All registered staff</p>
    </div>    

    <div class="bg-white p-5 rounded-lg shadow border-l-4 border-red-600">
        <p class="text-sm text-gray-500">Pending Approvals</p>
        <h2 class="text-2xl font-bold text-red-600">{{ $pendingApprovals }}</h2>
        <p class="text-xs text-gray-400 mt-1">Requires action</p>
    </div>

    <div class="bg-white p-5 rounded-lg shadow border-l-4 border-yellow-500">
        <p class="text-sm text-gray-500">Compliance Alerts</p>
        <h2 class="text-2xl font-bold text-yellow-600">{{ $complianceAlerts }}</h2>
        <p class="text-xs text-gray-400 mt-1">Expiring documents</p>
    </div>

</div>

<!-- FLOATING QUICK ACTION BUTTON -->
<div class="fixed right-6 bottom-8 z-50">

    <!-- ACTION TOGGLE -->
    <button id="quickActionToggle"
    class="flex items-center gap-2 px-5 py-3 rounded-full bg-black text-white shadow-2xl hover:bg-gray-800 transition">

    <!-- ICON -->
    <svg xmlns="http://www.w3.org/2000/svg"
         class="w-5 h-5"
         fill="none"
         viewBox="0 0 24 24"
         stroke="currentColor">

        <path stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M4 6h16M4 12h16M4 18h16" />

    </svg>

    <span class="text-sm font-medium">
        Quick Actions
    </span>

</button>

    <!-- QUICK ACTION MENU -->
    <div id="quickActionMenu"
        class="hidden absolute bottom-16 right-0 w-64 bg-white border border-gray-100 rounded-2xl shadow-2xl overflow-hidden">

        <!-- HEADER -->
        <div class="px-5 py-4 border-b bg-gray-50">

            <h3 class="font-semibold text-gray-800">
                Quick Actions
            </h3>

            <p class="text-xs text-gray-500 mt-1">
                Workforce shortcuts
            </p>

        </div>

        <!-- LINKS -->
        <div class="p-2">

            <a href="/admin/employees"
               class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-50 transition">

                👷

                <div>

                    <p class="text-sm font-medium text-gray-800">
                        Employees
                    </p>

                    <p class="text-xs text-gray-500">
                        Manage workforce
                    </p>

                </div>

            </a>

            <a href="/admin/applications"
               class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-50 transition">

                📥

                <div>

                    <p class="text-sm font-medium text-gray-800">
                        Applications
                    </p>

                    <p class="text-xs text-gray-500">
                        Review applicants
                    </p>

                </div>

            </a>

            <a href="/admin/compliance"
               class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-50 transition">

                🛡

                <div>

                    <p class="text-sm font-medium text-gray-800">
                        Compliance
                    </p>

                    <p class="text-xs text-gray-500">
                        Expiry monitoring
                    </p>

                </div>

            </a>

            <a href="/admin/payroll"
               class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-50 transition">

                💰

                <div>

                    <p class="text-sm font-medium text-gray-800">
                        Payroll
                    </p>

                    <p class="text-xs text-gray-500">
                        Payment management
                    </p>

                </div>

            </a>

        </div>

    </div>

</div>

<!-- APPLICATIONS & EMPLOYEES SECTION -->
<div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-6">

    <!-- CARD 1 : RECENT APPLICATIONS -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 h-[700px] flex flex-col">

        <!-- HEADER -->
        <div class="flex items-center justify-between mb-6">

            <div>
                <h3 class="text-lg font-semibold text-gray-800">
                    Recent Applications
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    Newly submitted workforce applications
                </p>
            </div>

            <a href="/admin/applications"
               class="text-sm font-medium text-red-600 hover:text-red-700">
                View All
            </a>

        </div>

        <!-- SCROLLABLE APPLICATION LIST -->
        <div class="space-y-4 overflow-y-auto pr-2 flex-1">

            @forelse($applications as $app)

                @php
                    $displayStep = min($app->registration_step, 6);
                    $progress = ($displayStep / 6) * 100;

                    $statusClasses = [
                        'approved' => 'bg-green-100 text-green-700',
                        'rejected' => 'bg-red-100 text-red-700',
                        'pending' => 'bg-yellow-100 text-yellow-700',
                        'under_review' => 'bg-blue-100 text-blue-700',
                    ];
                @endphp

                <div class="border border-gray-100 rounded-2xl p-4 hover:border-red-100 transition">

                    <!-- TOP -->
                    <div class="flex items-start justify-between gap-4">

                        <div class="flex items-center gap-3">

                            <!-- AVATAR -->
                            <div class="w-12 h-12 rounded-full bg-black text-white flex items-center justify-center text-sm font-semibold shrink-0">

                                {{ strtoupper(substr($app->name, 0, 1)) }}

                            </div>

                            <!-- DETAILS -->
                            <div>

                                <p class="font-semibold text-gray-800">
                                    {{ $app->name }}
                                </p>

                                <p class="text-sm text-gray-500">
                                    {{ $app->email }}
                                </p>

                            </div>

                        </div>

                        <!-- STATUS -->
                        <span class="px-3 py-1 rounded-full text-xs font-medium whitespace-nowrap
                            {{ $statusClasses[$app->approval_status] ?? 'bg-gray-100 text-gray-700' }}">

                            {{ ucwords(str_replace('_', ' ', $app->approval_status ?? 'pending')) }}

                        </span>

                    </div>

                    <!-- PROGRESS -->
                    <div class="mt-5">

                        <div class="flex items-center justify-between text-xs text-gray-500 mb-2">

                            <span>
                                Registration Progress
                            </span>

                            <span>
                                Step {{ $displayStep }}/6 ({{ round($progress) }}%)
                            </span>

                        </div>

                        <div class="w-full bg-gray-200 rounded-full h-2">

                            <div class="bg-red-600 h-2 rounded-full"
                                 style="width: {{ $progress }}%">
                            </div>

                        </div>

                    </div>

                    <!-- FOOTER -->
                    <div class="mt-5 flex items-center justify-between border-t border-gray-100 pt-4">

                        <div>

                            <p class="text-sm text-gray-500">
                                Submitted
                            </p>

                            <p class="text-sm font-medium text-gray-700 mt-1">
                                {{ $app->created_at->format('M d, Y') }}
                            </p>

                        </div>

                        <a href="/admin/applications/{{ $app->id }}"
                           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-black text-white text-sm hover:bg-gray-800 transition">

                            Review

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="w-4 h-4"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M9 5l7 7-7 7"/>

                            </svg>

                        </a>

                    </div>

                </div>

            @empty

                <div class="flex flex-col items-center justify-center py-20">

                    <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center text-2xl mb-4">
                        📄
                    </div>

                    <p class="text-sm font-medium text-gray-700">
                        No recent applications
                    </p>

                    <p class="text-xs text-gray-500 mt-1">
                        New workforce applications will appear here
                    </p>

                </div>

            @endforelse

        </div>

    </div>


    <!-- CARD 2 : EMPLOYEES -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 h-[700px] flex flex-col">

        <!-- HEADER -->
        <div class="flex items-center justify-between mb-6">

            <div>
                <h3 class="text-lg font-semibold text-gray-800">
                    Employees
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    Recently active workforce personnel
                </p>
            </div>

            <a href="/admin/employees"
               class="text-sm font-medium text-red-600 hover:text-red-700">
                View Employees
            </a>

        </div>

        <!-- SCROLLABLE EMPLOYEE LIST -->
        <!-- EMPLOYEES LIST -->
        <div class="space-y-4 overflow-y-auto pr-2 flex-1">

            @forelse($approvedEmployees as $employee)

                <div class="border border-gray-100 rounded-2xl p-4 hover:border-red-100 transition">

                    <!-- TOP -->
                    <div class="flex items-start justify-between gap-4">

                        <div class="flex items-center gap-3">

                            <!-- AVATAR -->
                            <div class="w-12 h-12 rounded-full bg-red-600 text-white flex items-center justify-center text-sm font-semibold shrink-0">

                                {{ strtoupper(substr($employee->name, 0, 1)) }}

                            </div>

                            <!-- DETAILS -->
                            <div>

                                <p class="font-semibold text-gray-800">
                                    {{ $employee->user->name }}
                                </p>

                                <p class="text-sm text-gray-500">
                                    {{ $employee->user->email }}
                                </p>

                            </div>

                        </div>

                        <!-- STATUS -->
                        <span class="px-3 py-1 rounded-full text-xs font-medium
                            {{ $employee->status == 'active'
                                ? 'bg-green-100 text-green-700'
                                : 'bg-gray-100 text-gray-700' }}">

                            {{ ucfirst($employee->user->status ?? 'Inactive') }}

                        </span>

                    </div>

                    <!-- DETAILS -->
                    <div class="grid grid-cols-2 gap-4 mt-5">

                        <!-- ROLE -->
                        <div>

                            <p class="text-xs text-gray-400">
                                Role
                            </p>

                            <p class="text-sm font-medium text-gray-700 mt-1">

                                @if($employee->user->role_id == 2)

                                    Supervisor

                                @elseif($employee->user->role_id == 3)

                                    {{ $employee->roleDetail->role_type ?? 'Staff' }}

                                @else

                                    N/A

                                @endif

                            </p>

                        </div>

                        <!-- DEPARTMENT -->
                        <div>

                            <!-- <p class="text-xs text-gray-400">
                                Department
                            </p>

                            <p class="text-sm font-medium text-gray-700 mt-1">
                                {{ $employee->department ?? 'N/A' }}
                            </p> -->

                        </div>

                    </div>

                    <!-- FOOTER -->
                    <div class="mt-5 flex items-center justify-between border-t border-gray-100 pt-4">

                        <div>

                            <p class="text-sm text-gray-500">
                                Joined
                            </p>

                            <p class="text-sm font-medium text-gray-700 mt-1">
                                {{ $employee->created_at->format('M d, Y') }}
                            </p>

                        </div>

                        <a href="/admin/employees/{{ $employee->id }}"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-black text-white text-sm hover:bg-gray-800 transition">

                            View

                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-4 h-4"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor">

                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 5l7 7-7 7"/>

                            </svg>

                        </a>

                    </div>

                </div>

            @empty

                <div class="flex flex-col items-center justify-center py-20">

                    <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center text-2xl mb-4">
                        👥
                    </div>

                    <p class="text-sm font-medium text-gray-700">
                        No employees found
                    </p>

                    <p class="text-xs text-gray-500 mt-1">
                        Employee records will appear here
                    </p>

                </div>

            @endforelse

        </div>
    </div>

</div>

<!-- MAIN DASHBOARD SECTION -->
<div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-6">

    <!-- CARD 1 : RECENT ACTIVITY -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 h-[650px] flex flex-col">

        <!-- HEADER -->
        <div class="flex items-center justify-between mb-6">

            <div>
                <h3 class="text-lg font-semibold text-gray-800">
                    Recent Activity
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    Latest operational and compliance events
                </p>
            </div>

            <button class="text-sm text-red-600 hover:text-red-700 font-medium">
                View Logs
            </button>

        </div>

        <!-- SCROLLABLE ACTIVITY FEED -->
        <div class="space-y-5 overflow-y-auto pr-2 flex-1">

            @forelse($activities as $activity)

                <div class="flex gap-4">

                    <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-lg shrink-0">

                        @if($activity->type == 'employee_registered')
                            ✔
                        @elseif($activity->type == 'compliance_alert')
                            ⚠
                        @elseif($activity->type == 'application_approved')
                            ✅
                        @else
                            📌
                        @endif

                    </div>

                    <div class="flex-1">

                        <div class="flex justify-between gap-3">

                            <p class="font-medium text-gray-800">
                                {{ $activity->title }}
                            </p>

                            <span class="text-xs text-gray-400 whitespace-nowrap">
                                {{ $activity->created_at->diffForHumans() }}
                            </span>

                        </div>

                        <p class="text-sm text-gray-500 mt-1">
                            {{ $activity->description }}
                        </p>

                    </div>

                </div>

            @empty

                <p class="text-sm text-gray-500">
                    No recent activity yet.
                </p>

            @endforelse

        </div>

    </div>


    <!-- CARD 2 : SHIFTS -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 h-[650px] flex flex-col">

        <!-- HEADER -->
        <div class="flex items-center justify-between mb-6">

            <div>
                <h3 class="text-lg font-semibold text-gray-800">
                    Shift Overview
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    Latest workforce shift assignments
                </p>
            </div>

            <a href="{{ route('shifts.index') }}"
               class="text-sm text-red-600 hover:text-red-700 font-medium">
                View Shifts
            </a>

        </div>

        <!-- SCROLLABLE SHIFT LIST -->
        <div class="space-y-4 overflow-y-auto pr-2 flex-1">

            @forelse($shifts->take(10) as $shift)

                <div class="border border-gray-100 rounded-2xl p-4 hover:border-red-100 transition">

                    <!-- TOP -->
                    <div class="flex items-start justify-between gap-3">

                        <div>
                            <h4 class="font-semibold text-gray-800">
                                {{ $shift->title }}
                            </h4>

                            <p class="text-sm text-gray-500 mt-1">
                                {{ $shift->location }}
                            </p>
                        </div>

                        <span class="
                            px-3 py-1 rounded-full text-xs font-medium

                            @if($shift->status == 'open')
                                bg-green-100 text-green-700
                            @elseif($shift->status == 'closed')
                                bg-gray-200 text-gray-700
                            @else
                                bg-yellow-100 text-yellow-700
                            @endif
                        ">
                            {{ ucfirst($shift->status) }}
                        </span>

                    </div>

                    <!-- SHIFT DETAILS -->
                    <div class="grid grid-cols-2 gap-4 mt-4">

                        <div>
                            <p class="text-xs text-gray-400">
                                Shift Date
                            </p>

                            <p class="text-sm font-medium text-gray-700 mt-1">
                                {{ \Carbon\Carbon::parse($shift->shift_date)->format('M d, Y') }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400">
                                Time
                            </p>

                            <p class="text-sm font-medium text-gray-700 mt-1">
                                {{ \Carbon\Carbon::parse($shift->start_time)->format('h:i A') }}
                                -
                                {{ \Carbon\Carbon::parse($shift->end_time)->format('h:i A') }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400">
                                Required Role
                            </p>

                            <p class="text-sm font-medium text-gray-700 mt-1">
                                {{ $shift->role_required }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400">
                                Staff Needed
                            </p>

                            <p class="text-sm font-medium text-gray-700 mt-1">
                                {{ $shift->required_staff }}
                            </p>
                        </div>

                    </div>

                    <!-- RATE -->
                    <div class="mt-4 flex items-center justify-between border-t border-gray-100 pt-3">

                        <span class="text-sm text-gray-500">
                            Hourly Rate
                        </span>

                        <span class="font-semibold text-red-600">
                            ₦{{ number_format($shift->hourly_rate, 2) }}/hr
                        </span>

                    </div>

                </div>

            @empty

                <div class="text-center py-10">

                    <p class="text-sm text-gray-500">
                        No shifts available yet.
                    </p>

                    <a href="{{ route('shifts.index') }}"
                       class="inline-flex mt-4 text-sm text-red-600 hover:text-red-700 font-medium">
                        Go to Shifts
                    </a>

                </div>

            @endforelse

        </div>

    </div>

</div>

<!-- SYSTEM STATUS -->
<div class="mt-6 bg-white p-6 rounded-lg shadow">

    <h3 class="font-semibold mb-4">System Status</h3>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">

        <div class="p-4 bg-gray-50 rounded">
            <p class="text-gray-500">Payroll Engine</p>
            <p class="font-semibold text-green-600">Active</p>
        </div>

        <div class="p-4 bg-gray-50 rounded">
            <p class="text-gray-500">GPS Tracking</p>
            <p class="font-semibold text-green-600">Running</p>
        </div>

        <div class="p-4 bg-gray-50 rounded">
            <p class="text-gray-500">Compliance System</p>
            <p class="font-semibold text-yellow-600">Monitoring</p>
        </div>

    </div>

</div>
<script>

    const quickActionToggle =
        document.getElementById('quickActionToggle');

    const quickActionMenu =
        document.getElementById('quickActionMenu');

    quickActionToggle?.addEventListener('click', function (e) {

        e.stopPropagation();

        quickActionMenu.classList.toggle('hidden');

    });

    document.addEventListener('click', function () {

        quickActionMenu?.classList.add('hidden');

    });

</script>
@endsection