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
                {{ $assignedCount }}
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Pending Assignments
            </p>

        </div>

        <!-- ACCEPTED -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">

            <div class="flex items-center justify-between mb-4">

                <div class="w-12 h-12 rounded-xl bg-green-100 text-green-600 flex items-center justify-center">
                    <i class="fas fa-check-circle"></i>
                </div>

                <span class="text-xs bg-green-50 text-green-700 px-3 py-1 rounded-full">
                    Accepted
                </span>

            </div>

            <h2 class="text-3xl font-bold text-gray-900">
                {{ $acceptedCount }}
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Accepted Shifts
            </p>

        </div>

        <!-- DECLINED -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">

            <div class="flex items-center justify-between mb-4">

                <div class="w-12 h-12 rounded-xl bg-red-100 text-red-600 flex items-center justify-center">
                    <i class="fas fa-times-circle"></i>
                </div>

                <span class="text-xs bg-red-50 text-red-700 px-3 py-1 rounded-full">
                    Declined
                </span>

            </div>

            <h2 class="text-3xl font-bold text-gray-900">
                {{ $declinedCount }}
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Declined Shifts
            </p>

        </div>

        <!-- COMPLETED -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">

            <div class="flex items-center justify-between mb-4">

                <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                    <i class="fas fa-briefcase"></i>
                </div>

                <span class="text-xs bg-blue-50 text-blue-700 px-3 py-1 rounded-full">
                    Completed
                </span>

            </div>

            <h2 class="text-3xl font-bold text-gray-900">
                {{ $completedCount }}
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Completed Shifts
            </p>

        </div>

    </div>

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
                    Assigned
                </a>

                <a href="{{ route('staff.shifts.index', ['status' => 'Accepted']) }}"
                class="px-4 py-2 rounded-xl text-sm font-medium transition
                {{ request('status') == 'Accepted' ? 'bg-green-600 text-white' : 'bg-green-100 text-green-700 hover:bg-green-200' }}">
                    Accepted
                </a>

                <a href="{{ route('staff.shifts.index', ['status' => 'Declined']) }}"
                class="px-4 py-2 rounded-xl text-sm font-medium transition
                {{ request('status') == 'Declined' ? 'bg-red-600 text-white' : 'bg-red-100 text-red-700 hover:bg-red-200' }}">
                    Declined
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

    <!-- SHIFTS -->
    <div class="space-y-5">

        @forelse($assignments as $assignment)

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

                <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-6">

                    <!-- SHIFT DETAILS -->
                    <div class="space-y-4 flex-1">

                        <div class="flex flex-wrap items-center gap-3">

                            <h2 class="text-xl font-semibold text-gray-900">
                                {{ $assignment->shift->title }}
                            </h2>

                            <!-- STATUS -->
                            @if($assignment->status == 'Assigned')
                                <span class="bg-yellow-100 text-yellow-700 text-xs px-3 py-1 rounded-full">
                                    Assigned
                                </span>
                            @elseif($assignment->status == 'Accepted')
                                <span class="bg-green-100 text-green-700 text-xs px-3 py-1 rounded-full">
                                    Accepted
                                </span>
                            @elseif($assignment->status == 'Declined')
                                <span class="bg-red-100 text-red-700 text-xs px-3 py-1 rounded-full">
                                    Declined
                                </span>
                            @else
                                <span class="bg-blue-100 text-blue-700 text-xs px-3 py-1 rounded-full">
                                    Completed
                                </span>
                            @endif

                        </div>

                        <p class="text-sm text-gray-600">
                            {{ $assignment->shift->description }}
                        </p>

                        <!-- INFO GRID -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                            <div>
                                <p class="text-xs text-gray-500">
                                    Shift Date
                                </p>

                                <p class="font-medium text-gray-900">
                                    {{ \Carbon\Carbon::parse($assignment->shift->shift_date)->format('d M Y') }}
                                </p>
                            </div>

                            <div>
                                <p class="text-xs text-gray-500">
                                    Time
                                </p>

                                <p class="font-medium text-gray-900">
                                    {{ \Carbon\Carbon::parse($assignment->shift->start_time)->format('h:i A') }}
                                    -
                                    {{ \Carbon\Carbon::parse($assignment->shift->end_time)->format('h:i A') }}
                                </p>
                            </div>

                            <div>
                                <p class="text-xs text-gray-500">
                                    Location
                                </p>

                                <p class="font-medium text-gray-900">
                                    {{ $assignment->shift->location }}
                                </p>
                            </div>

                            <div>
                                <p class="text-xs text-gray-500">
                                    Hourly Rate
                                </p>

                                <p class="font-semibold text-green-600">
                                    £{{ number_format($assignment->shift->hourly_rate, 2) }}/hr
                                </p>
                            </div>

                        </div>

                    </div>

                    <!-- ACTIONS -->
                    <div class="flex flex-col sm:flex-row gap-3">

                        @if($assignment->status == 'Assigned')

                            <!-- ACCEPT -->
                            <form action="{{ route('staff.shifts.accept', $assignment->id) }}" method="POST">
                                @csrf

                                <button type="submit"
                                    class="bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded-xl text-sm font-medium transition">
                                    Accept Shift
                                </button>
                            </form>

                            <!-- DECLINE -->
                            <form action="{{ route('staff.shifts.decline', $assignment->id) }}" method="POST">
                                @csrf

                                <button type="submit"
                                    class="bg-red-600 hover:bg-red-700 text-white px-5 py-3 rounded-xl text-sm font-medium transition">
                                    Decline Shift
                                </button>
                            </form>

                            <a href="#"
                            class="border border-gray-200 hover:bg-gray-100 text-gray-700 px-5 py-3 rounded-xl text-sm font-medium transition text-center">
                                View Details
                            </a>

                        @elseif($assignment->status == 'Accepted')

                            <button
                                class="bg-green-100 text-green-700 px-5 py-3 rounded-xl text-sm font-medium cursor-default">
                                Shift Accepted
                            </button>

                        @elseif($assignment->status == 'Declined')

                            <button
                                class="bg-red-100 text-red-700 px-5 py-3 rounded-xl text-sm font-medium cursor-default">
                                Shift Declined
                            </button>

                        @else

                            <button
                                class="bg-blue-100 text-blue-700 px-5 py-3 rounded-xl text-sm font-medium cursor-default">
                                Shift Completed
                            </button>

                        @endif

                    </div>

                </div>

            </div>

        @empty

            <!-- EMPTY STATE -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">

                <div class="flex justify-center mb-4">

                    <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 text-3xl">
                        <i class="fas fa-calendar-times"></i>
                    </div>

                </div>

                <h3 class="text-lg font-semibold text-gray-800 mb-2">
                    No Assigned Shifts
                </h3>

                <p class="text-sm text-gray-500">
                    You currently do not have any assigned shifts.
                </p>

            </div>

        @endforelse

    </div>

    <!-- PAGINATION -->
    <div>
        {{ $assignments->links() }}
    </div>

</div>

@endsection