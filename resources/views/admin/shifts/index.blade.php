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
            <div class="flex items-center justify-between mt-6 pt-5 border-t border-gray-200">

                <!-- EDIT -->
                <button onclick="openEditShiftModal({{ $shift->id }})"
                    class="flex items-center gap-2 text-sm font-medium text-blue-600
                        border border-blue-200 bg-blue-50/40
                        hover:bg-blue-100 hover:border-blue-300
                        px-3 py-1.5 rounded-lg transition">

                    ✏️ <span>Edit</span>

                </button>

                <!-- ASSIGN -->
                <button onclick="openAssignShiftModal({{ $shift->id }})"
                    class="flex items-center gap-2 text-sm font-medium text-purple-600
                        border border-purple-200 bg-purple-50/40
                        hover:bg-purple-100 hover:border-purple-300
                        px-3 py-1.5 rounded-lg transition">

                    👥 <span>Assign</span>

                </button>

                <!-- COMPLETE -->
                @if($shift->status !== 'Completed')

                    <button onclick="markShiftCompleted({{ $shift->id }})"
                        class="flex items-center gap-2 text-sm font-medium text-green-600
                            border border-green-200 bg-green-50/40
                            hover:bg-green-100 hover:border-green-300
                            px-3 py-1.5 rounded-lg transition">

                        ✅ <span>Complete</span>

                    </button>

                @else

                    <span class="text-xs px-3 py-1 rounded-lg bg-gray-100 text-gray-500 border border-gray-200">
                        Completed
                    </span>

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

<!-- EDIT SHIFT MODAL -->
<div id="editShiftModal"
     class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">

    <div class="bg-white w-full max-w-2xl rounded-2xl shadow-xl p-6">

        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Edit Shift</h2>

            <button onclick="closeEditShiftModal()" class="text-gray-500">
                ✕
            </button>
        </div>

        <form id="editShiftForm">

            <input type="hidden" id="edit_shift_id">

            <div class="grid grid-cols-2 gap-4">

                <div>
                    <label class="text-sm">Title</label>
                    <input id="edit_title" class="w-full border p-2 rounded">
                </div>

                <div>
                    <label class="text-sm">Date</label>
                    <input id="edit_shift_date" type="date" class="w-full border p-2 rounded">
                </div>

                <div>
                    <label class="text-sm">Start Time</label>
                    <input id="edit_start_time" type="time" class="w-full border p-2 rounded">
                </div>

                <div>
                    <label class="text-sm">End Time</label>
                    <input id="edit_end_time" type="time" class="w-full border p-2 rounded">
                </div>

                <div>
                    <label class="text-sm">Location</label>
                    <input id="edit_location" class="w-full border p-2 rounded">
                </div>

                <div>
                    <label class="text-sm">Required Staff</label>
                    <input id="edit_required_staff" type="number" class="w-full border p-2 rounded">
                </div>

                <div>
                    <label class="text-sm">Hourly Rate</label>
                    <input id="edit_hourly_rate" class="w-full border p-2 rounded">
                </div>

                <div>
                    <label class="text-sm">Status</label>
                    <select id="edit_status" class="w-full border p-2 rounded">
                        <option value="Open">Open</option>
                        <option value="Assigned">Assigned</option>
                        <option value="Completed">Completed</option>
                        <option value="Cancelled">Cancelled</option>
                    </select>
                </div>

            </div>

            <button class="mt-5 bg-black text-white px-4 py-2 rounded w-full">
                Save Changes
            </button>

        </form>

    </div>
</div>

<!-- CONFIRM COMPLETE MODAL -->
<div id="completeShiftModal"
     class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">

    <div class="bg-white w-full max-w-md rounded-2xl shadow-xl p-6 text-center">

        <h2 class="text-lg font-semibold mb-2">
            Mark Shift as Completed?
        </h2>

        <p class="text-sm text-gray-500 mb-6">
            This action will mark the shift as completed and cannot be undone.
        </p>

        <input type="hidden" id="complete_shift_id">

        <div class="flex gap-3 justify-center">

            <button onclick="closeCompleteShiftModal()"
                    class="px-4 py-2 rounded-lg border">
                Cancel
            </button>

            <button onclick="confirmCompleteShift()"
                    class="px-4 py-2 rounded-lg bg-green-600 text-white">
                Yes, Complete
            </button>

        </div>

    </div>
</div>

<!-- ASSIGN SHIFT MODAL -->
<div id="assignShiftModal"
     class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">

    <div class="bg-white w-full max-w-4xl rounded-2xl shadow-xl p-6 max-h-[90vh] overflow-y-auto">

        <!-- HEADER -->
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">
                Assign Staff to Shift
            </h2>
            <input type="hidden" id="assign_shift_id">
            <button onclick="closeAssignShiftModal()">✕</button>
        </div>

        <!-- SHIFT INFO -->
        <div id="assign_shift_info"
            class="p-4 bg-gray-50 rounded-lg mb-4 text-sm text-gray-700">

            <!-- JS will inject content -->

        </div>

        <!-- ALREADY ASSIGNED -->
        <div class="mb-4">

            <h3 class="font-semibold mb-2">
                Already Assigned Staff
            </h3>

            <div id="assigned_staff_list"
                 class="space-y-2 text-sm text-gray-600">
            </div>

        </div>

        <!-- ALL STAFF -->
        <div>

            <h3 class="font-semibold mb-2">
                Select Staff
            </h3>

            <div id="all_staff_list"
                 class="grid grid-cols-1 md:grid-cols-2 gap-2">
            </div>

        </div>

        <!-- BUTTON -->
        <div class="mt-6 flex justify-end">

            <button onclick="assignStaffToShift()"
                    class="bg-purple-600 text-white px-4 py-2 rounded-lg">
                Assign Staff
            </button>

        </div>

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
<script>
    function markShiftCompleted(id)
{
    document.getElementById('complete_shift_id').value = id;

    const modal = document.getElementById('completeShiftModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeCompleteShiftModal()
{
    const modal = document.getElementById('completeShiftModal');

    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// confirm + complete shift
async function confirmCompleteShift()
{
    const id = document.getElementById('complete_shift_id').value;

    try {

        const response = await fetch(`/admin/shifts/${id}/complete`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Failed to complete shift');
        }

        closeCompleteShiftModal();

        location.reload(); // 🔥 live refresh

        alert('Shift marked as completed successfully');

    } catch (error) {

        console.error(error);
        alert(error.message);
    }
}


</script>

<script>
    
/*
|--------------------------------------------------------------------------
| OPEN EDIT SHIFT MODAL
|--------------------------------------------------------------------------
| - Fetch shift
| - Populate modal
| - Open modal safely
*/
window.openEditShiftModal = async function (id)
{
    try {

        const response = await fetch(`/admin/shifts/${id}`, {
            headers: {
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error('Unable to load shift data');
        }

        const shift = await response.json();

        console.log('EDIT SHIFT DATA:', shift);

        // =========================
        // FILL FORM FIELDS
        // =========================
        document.getElementById('edit_shift_id').value = shift.id ?? '';

        document.getElementById('edit_title').value = shift.title ?? '';
        document.getElementById('edit_shift_date').value = shift.shift_date ?? '';
        document.getElementById('edit_start_time').value = shift.start_time ?? '';
        document.getElementById('edit_end_time').value = shift.end_time ?? '';
        document.getElementById('edit_location').value = shift.location ?? '';
        document.getElementById('edit_required_staff').value = shift.required_staff ?? '';
        document.getElementById('edit_hourly_rate').value = shift.hourly_rate ?? '';
        document.getElementById('edit_status').value = shift.status ?? 'Open';

        // =========================
        // OPEN MODAL
        // =========================
        const modal = document.getElementById('editShiftModal');

        modal.classList.remove('hidden');
        modal.classList.add('flex');

    } catch (error) {

        console.error(error);
        alert(error.message || 'Failed to load shift');
    }
};


/*
|--------------------------------------------------------------------------
| CLOSE EDIT MODAL
|--------------------------------------------------------------------------
*/
function closeEditShiftModal()
{
    const modal = document.getElementById('editShiftModal');

    modal.classList.add('hidden');
    modal.classList.remove('flex');
}


/*
|--------------------------------------------------------------------------
| SUBMIT UPDATE SHIFT (AJAX)
|--------------------------------------------------------------------------
*/
document.getElementById('editShiftForm')
.addEventListener('submit', async function (e)
{
    e.preventDefault();

    const id = document.getElementById('edit_shift_id').value;

    const payload = {
        title: document.getElementById('edit_title').value,
        shift_date: document.getElementById('edit_shift_date').value,
        start_time: document.getElementById('edit_start_time').value,
        end_time: document.getElementById('edit_end_time').value,
        location: document.getElementById('edit_location').value,
        required_staff: document.getElementById('edit_required_staff').value,
        hourly_rate: document.getElementById('edit_hourly_rate').value,
        status: document.getElementById('edit_status').value,
    };

    try {

        const response = await fetch(`/admin/shifts/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(payload)
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Shift update failed');
        }

        // =========================
        // SUCCESS FLOW
        // =========================
        closeEditShiftModal();

        location.reload(); // 🔥 refresh list instantly

        alert('Shift updated successfully');

    } catch (error) {

        console.error(error);
        alert(error.message);
    }
});
</script>
<script>
    window.openAssignShiftModal = async function (shiftId)
{
    let SHIFT_MAX = 0;
    let SHIFT_ASSIGNED = 0;
    try {

        const res = await fetch(`/admin/shifts/${shiftId}/assign-data`, {
            headers: { 'Accept': 'application/json' }
        });

        const data = await res.json();

        const shift = data.shift;
        const assigned = data.assigned;
        const staff = data.staff;

        document.getElementById('assign_shift_id').value = shift.id;

        // SHIFT INFO
        document.getElementById('assign_shift_info').innerHTML = `
            <strong class="text-base">${shift.title}</strong><br>

            📍 ${shift.location ?? 'N/A'}<br>
            ⏰ ${shift.start_time} - ${shift.end_time}<br>
            📅 ${shift.shift_date}<br>

            👥 <span class="font-semibold">
                Assigned: ${assigned.length} / ${shift.required_staff}
            </span>
        `;

        // ASSIGNED STAFF
        document.getElementById('assigned_staff_list').innerHTML =
            assigned.length
                ? assigned.map(a => `<div>👤 ${a.employee.user.name}</div>`).join('')
                : '<p class="text-gray-400">No staff assigned yet</p>';

        // ALL STAFF WITH CHECKBOXES
        document.getElementById('all_staff_list').innerHTML =
            staff.map(s => `
                <label class="flex items-center gap-2 border p-2 rounded">
                    <input type="checkbox" value="${s.id}" class="assign_staff_checkbox">
                    <span>${s.user.name}</span>
                </label>
            `).join('');

        const modal = document.getElementById('assignShiftModal');

        modal.classList.remove('hidden');
        modal.classList.add('flex');

    } catch (err) {

        console.error(err);
        alert('Failed to load shift assignment data');
    }
};


function closeAssignShiftModal()
{
    const modal = document.getElementById('assignShiftModal');

    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
</script>

<script>
   async function assignStaffToShift()
{
    const shiftId = document.getElementById('assign_shift_id').value;

    const selected = Array.from(
        document.querySelectorAll('.assign_staff_checkbox:checked')
    ).map(cb => cb.value);

    // get shift limit from modal text or store globally
    const shiftInfoText = document.getElementById('assign_shift_info').innerText;

    const match = shiftInfoText.match(/Assigned:\s*(\d+)\s*\/\s*(\d+)/);

    const currentAssigned = match ? parseInt(match[1]) : 0;
    const maxAllowed = match ? parseInt(match[2]) : 999;

    const totalAfterAssign = currentAssigned + selected.length;

    if (totalAfterAssign > maxAllowed) {
        alert(`You can only assign ${maxAllowed} staff total. Currently assigned: ${currentAssigned}`);
        return;
    }

    try {

        const res = await fetch(`/admin/shifts/${shiftId}/assign`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ employees: selected })
        });

        const data = await res.json();

        if (!res.ok) throw new Error(data.message);

        closeAssignShiftModal();

        await loadShifts();

        alert('Staff assigned successfully');

    } catch (err) {

        console.error(err);
        alert(err.message);
    }
}
</script>
@endsection