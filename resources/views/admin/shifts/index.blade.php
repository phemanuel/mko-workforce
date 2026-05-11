@extends('layouts.admin')

@section('content')

<div class="p-6">

    <!-- HEADER -->
    <div class="flex items-center justify-between mb-6">

        <div>

            <h1 class="text-2xl font-bold text-gray-900">
                Shift Management
            </h1>

            <p class="text-sm text-gray-500 mt-1">
                Manage workforce scheduling and assignments
            </p>

        </div>

        <button onclick="openCreateShiftModal()"
                class="bg-black hover:bg-gray-800 text-white px-5 py-3 rounded-xl text-sm font-medium shadow">

            + Create Shift

        </button>

    </div>

    <!-- SHIFT STATS -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">

        <div class="bg-white rounded-2xl p-5 shadow-sm border">
            <p class="text-sm text-gray-500">Total Shifts</p>
            <h2 class="text-3xl font-bold mt-2">
                {{ $shifts->count() }}
            </h2>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border">
            <p class="text-sm text-gray-500">Open</p>
            <h2 class="text-3xl font-bold mt-2 text-blue-600">
                {{ $shifts->where('status', 'Open')->count() }}
            </h2>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border">
            <p class="text-sm text-gray-500">Assigned</p>
            <h2 class="text-3xl font-bold mt-2 text-yellow-600">
                {{ $shifts->where('status', 'Assigned')->count() }}
            </h2>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border">
            <p class="text-sm text-gray-500">Completed</p>
            <h2 class="text-3xl font-bold mt-2 text-green-600">
                {{ $shifts->where('status', 'Completed')->count() }}
            </h2>
        </div>

    </div>

    <!-- SHIFT LIST -->
<div class="bg-white rounded-3xl shadow-sm border overflow-hidden">

    <!-- HEADER -->
    <div class="flex items-center justify-between px-6 py-5 border-b bg-gray-50">

        <div class="flex-1 max-w-xl">

            <div class="relative">

                <div class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                    🔍
                </div>

                <input type="text"
                    id="shiftSearch"
                    placeholder="Search by title, role, location or status..."
                    class="w-full bg-white border border-gray-200 rounded-2xl py-3 pl-12 pr-4 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-black/10 focus:border-black transition">

            </div>

        </div>

        <div class="bg-black text-white text-sm px-4 py-2 rounded-2xl">
            {{ $shifts->count() }} Total
        </div>

    </div>

    <!-- GRID -->
    <div id="shiftList"
         class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 p-6">

        @forelse($shifts as $shift)

            @php

                $cardColor = match($shift->status) {

                    'Open' => 'from-blue-50 to-cyan-50 border-blue-100',
                    'Assigned' => 'from-yellow-50 to-amber-50 border-yellow-100',
                    'Completed' => 'from-green-50 to-emerald-50 border-green-100',
                    'Cancelled' => 'from-red-50 to-rose-50 border-red-100',

                    default => 'from-gray-50 to-slate-50 border-gray-100'
                };

                $badgeColor = match($shift->status) {

                    'Open' => 'bg-blue-100 text-blue-700',
                    'Assigned' => 'bg-yellow-100 text-yellow-700',
                    'Completed' => 'bg-green-100 text-green-700',
                    'Cancelled' => 'bg-red-100 text-red-700',

                    default => 'bg-gray-100 text-gray-700'
                };

            @endphp

            <!-- CARD -->
            <div class="rounded-3xl border bg-gradient-to-br {{ $cardColor }} p-5 shadow-sm hover:shadow-lg transition duration-300">

                <!-- TOP -->
                <div class="flex items-start justify-between gap-4">

                    <div>

                        <h3 class="shift-title text-lg font-bold text-gray-900">
                            {{ $shift->title }}
                        </h3>

                        <p class="shift-role text-sm text-gray-500 mt-1">
                            {{ $shift->role_required }}
                        </p>

                    </div>

                    <span class="shift-status px-3 py-1 rounded-full text-xs font-semibold {{ $badgeColor }}">

                        {{ $shift->status }}

                    </span>

                </div>

                <!-- DETAILS -->
                <div class="mt-5 space-y-3 text-sm text-gray-700">

                    <div class="flex items-center gap-2">

                        <div class="w-9 h-9 rounded-xl bg-white flex items-center justify-center shadow-sm">
                            📅
                        </div>

                        <div>
                            <p class="text-xs text-gray-500">
                                Shift Date
                            </p>

                            <p class="font-medium">
                                {{ $shift->shift_date }}
                            </p>
                        </div>

                    </div>

                    <div class="flex items-center gap-2">

                        <div class="w-9 h-9 rounded-xl bg-white flex items-center justify-center shadow-sm">
                            ⏰
                        </div>

                        <div>
                            <p class="text-xs text-gray-500">
                                Working Hours
                            </p>

                            <p class="font-medium">
                                {{ $shift->start_time }} - {{ $shift->end_time }}
                            </p>
                        </div>

                    </div>

                    <div class="flex items-center gap-2">

                        <div class="w-9 h-9 rounded-xl bg-white flex items-center justify-center shadow-sm">
                            📍
                        </div>

                        <div>
                            <p class="text-xs text-gray-500">
                                Location
                            </p>

                            <p class="shift-location font-medium">
                                {{ $shift->location ?? 'N/A' }}
                            </p>
                        </div>

                    </div>

                    <div class="grid grid-cols-2 gap-3 pt-2">

                        <div class="bg-white rounded-2xl p-3 shadow-sm">

                            <p class="text-xs text-gray-500">
                                Staff Needed
                            </p>

                            <p class="font-bold text-gray-800 mt-1">
                                {{ $shift->required_staff }}
                            </p>

                        </div>

                        <div class="bg-white rounded-2xl p-3 shadow-sm">

                            <p class="text-xs text-gray-500">
                                Hourly Rate
                            </p>

                            <p class="font-bold text-gray-800 mt-1">
                                £{{ number_format($shift->hourly_rate, 2) }}
                            </p>

                        </div>

                    </div>

                </div>

                <!-- ACTIONS -->
                <div class="flex items-center justify-between mt-6 pt-5 border-t border-white/60">

                    <button onclick="openEditShiftModal({{ $shift->id }})"
                            class="flex items-center gap-2 text-sm font-medium text-blue-700 hover:text-blue-900 transition">

                        ✏️
                        <span>Edit</span>

                    </button>

                    @if($shift->status !== 'Completed')

                        <button onclick="markShiftCompleted({{ $shift->id }})"
                                class="flex items-center gap-2 text-sm font-medium text-green-700 hover:text-green-900 transition">

                            ✅
                            <span>Complete</span>

                        </button>

                    @endif

                </div>

            </div>

        @empty

            <div class="col-span-full py-20 text-center">

                <div class="w-20 h-20 rounded-full bg-gray-100 mx-auto flex items-center justify-center text-3xl">
                    📅
                </div>

                <h3 class="mt-5 text-lg font-semibold text-gray-700">
                    No shifts available
                </h3>

                <p class="text-sm text-gray-500 mt-2">
                    Create a new shift to get started
                </p>

            </div>

        @endforelse

    </div>

</div>

</div>


<!-- CREATE SHIFT MODAL -->
<div id="createShiftModal"
     class="fixed inset-0 bg-black/50 hidden z-50 items-center justify-center p-4">

    <div class="bg-white w-full max-w-3xl rounded-2xl shadow-2xl overflow-hidden">

        <!-- HEADER -->
        <div class="flex items-center justify-between px-6 py-4 border-b">

            <h2 class="text-lg font-bold">
                Create Shift
            </h2>

            <button onclick="closeCreateShiftModal()"
                    class="text-gray-500 hover:text-black text-xl">

                ✕

            </button>

        </div>

        <!-- FORM -->
        <form id="createShiftForm"class="p-6 space-y-5">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <!-- TITLE -->
                <div>

                    <label class="text-sm font-medium text-gray-700">
                        Shift Title
                    </label>

                    <input type="text"
                        id="shift_title"
                        name="title"
                        class="w-full border rounded-xl p-3 mt-1">

                </div>

                <!-- ROLE -->
                <div>

                    <label class="text-sm font-medium text-gray-700">
                        Role Required
                    </label>

                    <select id="shift_role_required"
                            name="role_required"
                            class="w-full border rounded-xl p-3 mt-1">

                        <option value="">Select Role</option>

                        <option value="SIA Security">
                            SIA Security
                        </option>

                        <option value="Cleaning Services">
                            Cleaning Services
                        </option>

                        <option value="Companion Support">
                            Companion Support
                        </option>

                        <option value="Back Support Services">
                            Back Support Services
                        </option>

                    </select>

                </div>

                <!-- DATE -->
                <div>

                    <label class="text-sm font-medium text-gray-700">
                        Shift Date
                    </label>

                    <input type="date"
                        id="shift_date"
                        name="shift_date"
                        class="w-full border rounded-xl p-3 mt-1">

                </div>

                <!-- LOCATION -->
                <div>

                    <label class="text-sm font-medium text-gray-700">
                        Location
                    </label>

                    <input type="text"
                        id="shift_location"
                        name="location"
                        class="w-full border rounded-xl p-3 mt-1">

                </div>

                <!-- START -->
                <div>

                    <label class="text-sm font-medium text-gray-700">
                        Start Time
                    </label>

                    <input type="time"
                        id="shift_start_time"
                        name="start_time"
                        class="w-full border rounded-xl p-3 mt-1">

                </div>

                <!-- END -->
                <div>

                    <label class="text-sm font-medium text-gray-700">
                        End Time
                    </label>

                    <input type="time"
                        id="shift_end_time"
                        name="end_time"
                        class="w-full border rounded-xl p-3 mt-1">

                </div>

                <!-- STAFF -->
                <div>

                    <label class="text-sm font-medium text-gray-700">
                        Required Staff
                    </label>

                    <input type="number"
                        id="shift_required_staff"
                        name="required_staff"
                        class="w-full border rounded-xl p-3 mt-1">

                </div>

                <!-- RATE -->
                <div>

                    <label class="text-sm font-medium text-gray-700">
                        Hourly Rate (£)
                    </label>

                    <input type="number"
                        step="0.01"
                        id="shift_hourly_rate"
                        name="hourly_rate"
                        class="w-full border rounded-xl p-3 mt-1">

                </div>

            </div>

            <!-- DESCRIPTION -->
            <div>

                <label class="text-sm font-medium text-gray-700">
                    Description
                </label>

                <textarea id="shift_description"
                        name="description"
                        rows="4"
                        class="w-full border rounded-xl p-3 mt-1"></textarea>

            </div>

            <!-- BUTTON -->
            <div class="flex justify-end">

                <button type="submit"
                        class="bg-black hover:bg-gray-800 text-white px-6 py-3 rounded-xl">

                    Save Shift

                </button>

            </div>

        </form>

    </div>

</div>


<script>

function openCreateShiftModal()
{
    document.getElementById('createShiftModal')
        .classList.remove('hidden');

    document.getElementById('createShiftModal')
        .classList.add('flex');
}

function closeCreateShiftModal()
{
    document.getElementById('createShiftModal')
        .classList.add('hidden');

    document.getElementById('createShiftModal')
        .classList.remove('flex');
}


/*
|--------------------------------------------------------------------------
| CREATE SHIFT AJAX
|--------------------------------------------------------------------------
*/
document.getElementById('createShiftForm')
.addEventListener('submit', async function(e) {

    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);

    try {

        const response = await fetch("{{ route('shifts.store') }}", {

            method: 'POST',

            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },

            body: formData
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Unable to create shift');
        }

        /*
        |-----------------------------------------
        | CLEAN REFRESH (NO DUPLICATION BUGS)
        |-----------------------------------------
        */
        await loadShifts(); // 🔥 refresh entire list properly

        closeCreateShiftModal();

        form.reset();

        alert('Shift created successfully');

    }
    catch (error) {

        console.error(error);

        alert(error.message);
    }

});

</script>
<script>
    document.getElementById('shiftSearch')
.addEventListener('input', function () {

    const keyword = this.value.toLowerCase();

    const cards = document.querySelectorAll('#shiftList > div');

    cards.forEach(card => {

        const title = card.querySelector('.shift-title')?.innerText.toLowerCase() || '';
        const role = card.querySelector('.shift-role')?.innerText.toLowerCase() || '';
        const location = card.querySelector('.shift-location')?.innerText.toLowerCase() || '';
        const status = card.querySelector('.shift-status')?.innerText.toLowerCase() || '';

        const matches =
            title.includes(keyword) ||
            role.includes(keyword) ||
            location.includes(keyword) ||
            status.includes(keyword);

        card.style.display = matches ? 'block' : 'none';

    });

});
</script>
@endsection