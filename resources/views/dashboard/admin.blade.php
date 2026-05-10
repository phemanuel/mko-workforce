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

<!-- MAIN DASHBOARD SECTION -->
<div class="mt-6">

    <!-- RECENT ACTIVITY -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

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

        <!-- ACTIVITY FEED -->
        <div class="space-y-5">

    @forelse($activities as $activity)

        <div class="flex gap-4">

            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center">

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

                <div class="flex justify-between">

                    <p class="font-medium text-gray-800">
                        {{ $activity->title }}
                    </p>

                    <span class="text-xs text-gray-400">
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

<!-- APPLICATION QUEUE -->
<div class="mt-6 bg-white rounded-2xl shadow-sm border border-gray-100">

    <!-- HEADER -->
    <div class="flex items-center justify-between px-6 py-5 border-b">

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

    <!-- TABLE -->
    <div class="overflow-x-auto">

        <table class="w-full">

            <thead class="bg-gray-50 border-b">

                <tr class="text-left text-xs uppercase tracking-wider text-gray-500">

                    <th class="px-6 py-4 font-medium">
                        Applicant
                    </th>

                    <th class="px-6 py-4 font-medium">
                        Progress
                    </th>

                    <th class="px-6 py-4 font-medium">
                        Status
                    </th>

                    <th class="px-6 py-4 font-medium">
                        Submitted
                    </th>

                    <th class="px-6 py-4 font-medium text-right">
                        Action
                    </th>

                </tr>

            </thead>

            <tbody class="divide-y divide-gray-100">

                @forelse($applications as $app)

                    <tr class="hover:bg-gray-50 transition">

                        <!-- USER -->
                        <td class="px-6 py-4">

                            <div class="flex items-center gap-3">

                                <!-- AVATAR -->
                                <div class="w-10 h-10 rounded-full bg-black text-white flex items-center justify-center text-sm font-semibold">

                                    {{ strtoupper(substr($app->name, 0, 1)) }}

                                </div>

                                <!-- DETAILS -->
                                <div>

                                    <p class="font-medium text-gray-800">
                                        {{ $app->name }}
                                    </p>

                                    <p class="text-xs text-gray-500">
                                        {{ $app->email }}
                                    </p>

                                </div>

                            </div>

                        </td>

                        <!-- STEP -->
                        <td class="px-6 py-4">
                            @php
                                $displayStep = min($app->registration_step, 6);
                                $progress = ($displayStep / 6) * 100;
                            @endphp
                            <div class="flex flex-col gap-1">

                                <div class="flex items-center justify-between text-xs text-gray-500">

                                    <span>
                                        Step {{ $displayStep }}/6
                                    </span>

                                    <span>
                                        {{ round($progress) }}%
                                    </span>

                                </div>

                                <!-- PROGRESS -->
                                <div class="w-40 bg-gray-200 rounded-full h-2">

                                    <div class="bg-red-600 h-2 rounded-full"
                                         style="width: {{ $progress }}%"
                                    </div>

                                </div>

                            </div>

                        </td>

                        <!-- STATUS -->
                        <td class="px-6 py-4">

                            @php

                                $statusClasses = [
                                    'approved' => 'bg-green-100 text-green-700',
                                    'rejected' => 'bg-red-100 text-red-700',
                                    'pending' => 'bg-yellow-100 text-yellow-700',
                                    'under_review' => 'bg-blue-100 text-blue-700',
                                ];

                            @endphp

                            <span class="px-3 py-1 rounded-full text-xs font-medium
                                {{ $statusClasses[$app->approval_status] ?? 'bg-gray-100 text-gray-700' }}">

                                {{ ucwords(str_replace('_', ' ', $app->approval_status ?? 'pending')) }}

                            </span>

                        </td>

                        <!-- DATE -->
                        <td class="px-6 py-4 text-sm text-gray-500">

                            {{ $app->created_at->format('M d, Y') }}

                            <div class="text-xs text-gray-400 mt-1">
                                {{ $app->created_at->diffForHumans() }}
                            </div>

                        </td>

                        <!-- ACTION -->
                        <td class="px-6 py-4 text-right">

                            <a href="/admin/applications/{{ $app->id }}"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-black text-white text-sm hover:bg-gray-800 transition">

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

                        </td>
                    </tr>

                @empty

                    <tr>

                        <td colspan="5" class="px-6 py-10 text-center">

                            <div class="flex flex-col items-center">

                                <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center mb-3">

                                    📄

                                </div>

                                <p class="text-sm font-medium text-gray-700">
                                    No recent applications
                                </p>

                                <p class="text-xs text-gray-500 mt-1">
                                    New workforce applications will appear here
                                </p>

                            </div>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

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