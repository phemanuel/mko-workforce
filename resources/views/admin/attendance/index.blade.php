@extends('layouts.admin')

@section('content')

<div class="p-6">

    <!-- HEADER -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            Attendance Management
        </h1>

        <p class="text-sm text-gray-500 mt-1">
            Monitor shift attendance, check-ins, and staff performance
        </p>
    </div>

    <!-- SHIFTS GRID -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

        @forelse($shifts as $shift)

            @php
                $assignedCount = $shift->assignments->count();
                $attendanceCount = $shift->attendances->count();
            @endphp

            <div class="bg-white border rounded-2xl p-5 shadow-sm hover:shadow-md transition">

                <!-- SHIFT HEADER -->
                <div class="flex justify-between items-start">

                    <div>
                        <h2 class="font-semibold text-lg text-gray-900">
                            {{ $shift->title }}
                        </h2>

                        <p class="text-sm text-gray-500">
                            {{ $shift->role_required }}
                        </p>
                    </div>

                    <span class="text-xs px-3 py-1 rounded-full bg-gray-100 text-gray-700">
                        {{ $shift->status }}
                    </span>

                </div>

                <!-- SHIFT INFO -->
                <div class="mt-4 text-sm text-gray-600 space-y-1">

                    <p>📅 {{ $shift->shift_date }}</p>
                    <p>⏰ {{ $shift->start_time }} - {{ $shift->end_time }}</p>
                    <p>📍 {{ $shift->location ?? 'N/A' }}</p>

                </div>

                <!-- METRICS -->
                <div class="mt-5 grid grid-cols-2 gap-3">

                    <div class="bg-gray-50 rounded-xl p-3 text-center">
                        <p class="text-xs text-gray-500">Assigned</p>
                        <p class="font-bold text-gray-800">{{ $assignedCount }}</p>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-3 text-center">
                        <p class="text-xs text-gray-500">Attendance Logs</p>
                        <p class="font-bold text-gray-800">{{ $attendanceCount }}</p>
                    </div>

                </div>

                <!-- ACTION -->
                <div class="mt-5">

                    <a href="#"
                       class="block text-center bg-black text-white text-sm py-2 rounded-xl hover:bg-gray-800 transition">

                        View Attendance

                    </a>

                </div>

            </div>

        @empty

            <div class="col-span-full text-center py-20 text-gray-500">
                No shifts found
            </div>

        @endforelse

    </div>

</div>

@endsection