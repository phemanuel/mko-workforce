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

            <!-- <div class="bg-white/10 backdrop-blur-md rounded-xl p-4 min-w-[220px]">
                <p class="text-sm text-gray-300 mb-1">
                    Today's Date
                </p>

                <h3 class="text-xl font-semibold">
                    {{ now()->format('d M Y') }}
                </h3>

                <p class="text-sm text-green-400 mt-2">
                    ● Active Staff Account
                </p>
            </div> -->

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
                3
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
                18
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
                96%
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Attendance Rate
            </p>

        </div>

        <!-- PAYMENTS -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition">

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

                <span class="bg-green-100 text-green-700 text-xs px-3 py-1 rounded-full">
                    Active
                </span>

            </div>

            <div class="border border-gray-100 rounded-2xl p-5 bg-gray-50">

                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">

                    <div class="space-y-3">

                        <div>
                            <p class="text-sm text-gray-500">
                                Location
                            </p>

                            <h4 class="font-semibold text-lg text-gray-900">
                                Site A - Security Unit
                            </h4>
                        </div>

                        <div class="flex flex-wrap gap-6 text-sm">

                            <div>
                                <p class="text-gray-500">
                                    Start Time
                                </p>

                                <p class="font-medium text-gray-900">
                                    08:00 AM
                                </p>
                            </div>

                            <div>
                                <p class="text-gray-500">
                                    End Time
                                </p>

                                <p class="font-medium text-gray-900">
                                    06:00 PM
                                </p>
                            </div>

                        </div>

                    </div>

                    <div class="flex flex-col sm:flex-row gap-3">

                        <button class="bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded-xl text-sm font-medium transition">
                            Check In
                        </button>

                        <button class="bg-red-600 hover:bg-red-700 text-white px-5 py-3 rounded-xl text-sm font-medium transition">
                            Check Out
                        </button>

                    </div>

                </div>

            </div>

        </div>

        <!-- QUICK ACTIONS -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

            <h3 class="text-lg font-bold text-gray-900 mb-5">
                Quick Actions
            </h3>

            <div class="space-y-3">

                <a href="#" class="flex items-center justify-between p-4 rounded-xl border border-gray-100 hover:bg-gray-50 transition">

                    <div>
                        <p class="font-medium text-gray-800">
                            View My Shifts
                        </p>

                        <p class="text-xs text-gray-500">
                            See assigned shifts
                        </p>
                    </div>

                    <i class="fas fa-arrow-right text-gray-400"></i>

                </a>

                <a href="#" class="flex items-center justify-between p-4 rounded-xl border border-gray-100 hover:bg-gray-50 transition">

                    <div>
                        <p class="font-medium text-gray-800">
                            Attendance Records
                        </p>

                        <p class="text-xs text-gray-500">
                            Check attendance logs
                        </p>
                    </div>

                    <i class="fas fa-arrow-right text-gray-400"></i>

                </a>

                <a href="#" class="flex items-center justify-between p-4 rounded-xl border border-gray-100 hover:bg-gray-50 transition">

                    <div>
                        <p class="font-medium text-gray-800">
                            Payment History
                        </p>

                        <p class="text-xs text-gray-500">
                            Review all payments
                        </p>
                    </div>

                    <i class="fas fa-arrow-right text-gray-400"></i>

                </a>

            </div>

        </div>

    </div>

    <!-- RECENT PAYMENTS -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

        <div class="flex items-center justify-between mb-6">

            <div>
                <h3 class="text-lg font-bold text-gray-900">
                    Recent Payments
                </h3>

                <p class="text-sm text-gray-500">
                    Latest payment transactions
                </p>
            </div>

            <a href="#" class="text-sm text-black font-medium hover:underline">
                View All
            </a>

        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead>
                    <tr class="border-b text-left text-gray-500">
                        <th class="pb-3 font-medium">Period</th>
                        <th class="pb-3 font-medium">Role</th>
                        <th class="pb-3 font-medium">Status</th>
                        <th class="pb-3 font-medium text-right">Amount</th>
                    </tr>
                </thead>

                <tbody>

                    <tr class="border-b">
                        <td class="py-4">Week 1</td>
                        <td class="py-4">Security</td>
                        <td class="py-4">
                            <span class="bg-green-100 text-green-700 text-xs px-3 py-1 rounded-full">
                                Paid
                            </span>
                        </td>
                        <td class="py-4 text-right font-semibold">
                            £120
                        </td>
                    </tr>

                    <tr>
                        <td class="py-4">Week 2</td>
                        <td class="py-4">Cleaning</td>
                        <td class="py-4">
                            <span class="bg-yellow-100 text-yellow-700 text-xs px-3 py-1 rounded-full">
                                Pending
                            </span>
                        </td>
                        <td class="py-4 text-right font-semibold">
                            £110
                        </td>
                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection