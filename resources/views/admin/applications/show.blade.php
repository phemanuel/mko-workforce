@extends('layouts.admin')

@section('page-title', 'Application Review')

@section('content')

<div class="bg-white p-6 rounded shadow space-y-6">

    <!-- HEADER -->
    <div>
        <h2 class="text-2xl font-bold">
            {{ $user->name }}
        </h2>

        <p class="text-sm text-gray-500">
            {{ $user->email }}
        </p>

        <div class="flex gap-3 mt-2 text-sm">
            <span class="px-2 py-1 bg-gray-100 rounded">
                Step {{ $user->registration_step }}/7
            </span>

            <span class="px-2 py-1 rounded
                {{ $user->status === 'active' ? 'bg-green-100 text-green-600' : 'bg-yellow-100 text-yellow-600' }}">
                {{ ucfirst($user->status) }}
            </span>
        </div>
    </div>

    <hr>

    <!-- PROFILE -->
    <div>
        <h3 class="font-semibold flex items-center gap-1.5 mb-3">

    <svg xmlns="http://www.w3.org/2000/svg"
         class="w-4 h-4 text-black"
         fill="none" viewBox="0 0 24 24"
         stroke="currentColor">

        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M5.121 17.804A4 4 0 018 16h8a4 4 0 012.879 1.804M12 12a4 4 0 100-8 4 4 0 000 8z"/>
    </svg>

    Employee Profile
</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
            <p><span class="text-gray-500">Phone:</span> {{ $user->employee->profile->phone_no ?? 'N/A' }}</p>
            <p><span class="text-gray-500">DOB:</span> {{ \Carbon\Carbon::parse($user->employee->profile->date_of_birth)->format('F d, Y')?? 'N/A' }}</p>
            <p><span class="text-gray-500">Address:</span> {{ $user->employee->profile->address ?? 'N/A' }}</p>
            <p><span class="text-gray-500">Nationality:</span> {{ $user->employee->profile->nationality ?? 'N/A' }}</p>
        </div>
    </div>

    <hr>

    <!-- WORK ELIGIBILITY -->
    <div>
        <h3 class="font-semibold flex items-center gap-1.5 mb-3"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-black"
         fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 11c1.657 0 3-1.343 3-3S13.657 5 12 5
                 9 6.343 9 8s1.343 3 3 3zm0 2
                 c-4.418 0-8 2.239-8 5v1h16v-1
                 c0-2.761-3.582-5-8-5z"/>
    </svg> 
    Work Eligibility</h3>
        
        <div class="text-sm space-y-1">
            <p>Right to Work Status:
                <span class="font-medium">
                    {{ $user->employee->workEligibility->work_status ?? 'N/A' }}
                </span>
            </p>

            <p>Share Code:
                {{ $user->employee->workEligibility->share_code ?? 'N/A' }}
            </p>

            <p>Expiry:
                {{ \Carbon\Carbon::parse($user->employee->workEligibility->expiry_date)->format('F d, Y') ?? 'N/A' }}
            </p>
        </div>
    </div>

    <hr>

    <!-- ROLE DETAILS -->
    @php
        $roleDetail = $user->employee->roleDetail ?? null;

        // already an array OR null-safe fallback
        $data = $roleDetail->data ?? [];
    @endphp

    <div>
        <h3 class="font-semibold flex items-center gap-1.5 mb-3"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-black"
         fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5
                 a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414
                 A1 1 0 0119 9.414V19a2 2 0 01-2 2z"/>
    </svg>
    Role Details</h3>
        
        <!-- BASIC INFO -->
        <div class="text-sm space-y-1 mb-4">
            <p>
                <span class="font-medium">Primary Role:</span>
                {{ $roleDetail->role_type ?? 'N/A' }}
            </p>

            <!-- <p>
                <span class="font-medium">Secondary Skills:</span>
                {{ $roleDetail->secondary_skills ?? 'N/A' }}
            </p> -->
        </div>

        <!-- DYNAMIC ROLE FIELDS -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">

            @forelse($data as $key => $value)

                <div class="border rounded p-2 bg-gray-50">

                    <p class="text-gray-500 text-xs">
                        {{ ucwords(str_replace('_', ' ', $key)) }}
                    </p>

                    <div class="mt-1">

                        @php
                            $isImage = is_string($value) && preg_match('/\.(jpg|jpeg|png|webp)$/i', $value);
                        @endphp

                        @if($isImage)

                            <!-- IMAGE DISPLAY -->
                            <img src="{{ asset('storage/' . $value) }}"
                                class="w-full h-24 object-contain rounded border cursor-pointer"
                                onclick="openDocModal('{{ asset('storage/' . $value) }}')">

                        @elseif(is_array($value))

                            {{ implode(', ', $value) }}

                        @else

                            {{ $value }}

                        @endif

                    </div>

                </div>

            @empty
                <p class="text-gray-500 text-sm">No role-specific details provided.</p>
            @endforelse

        </div>
    </div>
    <hr>

    <!-- PAYROLL -->
    <div>
        <h3 class="font-semibold flex items-center gap-1.5 mb-3"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-black"
         fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6
                 a2 2 0 002 2h2m10 4h2a2 2 0 002-2v-6
                 a2 2 0 00-2-2h-2m-4 4h4"/>
    </svg>
    Payroll</h3>
        
        <div class="text-sm space-y-1">
            <p>Bank:
                {{ $user->employee->payroll->bank_name ?? 'N/A' }}
            </p>

            <p>Account:
                **** {{ substr($user->employee->payroll->account_number ?? '', -4) }}
            </p>

            <p>Payment Type:
                {{ $user->employee->payroll->payment_type ?? 'N/A' }}
            </p>
        </div>
    </div>

    <hr>

    <!-- DOCUMENTS -->
     <h3 class="font-semibold flex items-center gap-1.5 mb-3"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-black"
         fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M7 7h10M7 11h10M7 15h6M5 3h14a2 2 0 012 2v14
                 a2 2 0 01-2 2H5a2 2 0 01-2-2V5
                 a2 2 0 012-2z"/>
    </svg>
    Documents</h3>
     
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div id="toast"
        class="hidden fixed top-5 right-5 bg-black text-white px-4 py-2 rounded shadow-lg z-50">
    </div>

    @forelse($user->employee->documents ?? [] as $doc)

    <div class="border rounded p-3 space-y-2 bg-white shadow-sm">

        <p class="font-medium text-sm">
            {{ ucfirst(str_replace('_', ' ', $doc->document_type)) }}
        </p>

        <!-- STATUS -->
        @php
            $status = $doc->status ?: 'pending';
        @endphp

        <span id="status-{{ $doc->id }}"
            class="text-xs px-2 py-1 rounded
            @if($status == 'approved') bg-green-100 text-green-600
            @elseif($status == 'rejected') bg-red-100 text-red-600
            @else bg-yellow-100 text-yellow-600
            @endif">

            {{ ucfirst($status) }}
        </span>

        <!-- PREVIEW -->
        <div class="flex items-center justify-between mt-3">

        <!-- LEFT: VIEW -->
        <button onclick="openDocModal('{{ asset('storage/'.$doc->file_path) }}')"
            class="flex items-center justify-center w-8 h-8 rounded-md bg-gray-900 text-white hover:bg-black transition"
            title="Preview">

            <!-- eye -->
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M2.458 12C3.732 7.943 7.523 5 12 5
                        c4.478 0 8.268 2.943 9.542 7
                        -1.274 4.057-5.064 7-9.542 7
                        -4.477 0-8.268-2.943-9.542-7z"/>
            </svg>

        </button>

        <!-- RIGHT ACTIONS -->
        <div class="flex items-center gap-2">

            <!-- APPROVE -->
            <button onclick="updateDocStatus({{ $doc->id }}, 'approved')"
                class="flex items-center gap-1 px-2 py-1 text-xs rounded-md bg-green-600 text-white hover:bg-green-700 transition"
                title="Approve">

                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 13l4 4L19 7" />
                </svg>

                <span>Approve</span>
            </button>

            <!-- REJECT -->
            <button onclick="updateDocStatus({{ $doc->id }}, 'rejected')"
                class="flex items-center gap-1 px-2 py-1 text-xs rounded-md bg-red-600 text-white hover:bg-red-700 transition"
                title="Reject">

                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>

                <span>Reject</span>
            </button>

        </div>

    </div>

        @if($doc->rejection_reason)
            <p class="text-xs text-red-500">
                {{ $doc->rejection_reason }}
            </p>
        @endif

    </div>

    @empty
        <p class="text-sm text-gray-500">No documents uploaded.</p>
    @endforelse

    </div>

    <hr>

    <!-- ACTIONS -->
    <div class="flex gap-3">

        <form method="POST" action="/admin/applications/{{ $user->id }}/approve">
            @csrf
            <button class="bg-green-600 text-white px-4 py-2 rounded">
                Approve
            </button>
        </form>

        <form method="POST" action="/admin/applications/{{ $user->id }}/reject">
            @csrf
            <button class="bg-red-600 text-white px-4 py-2 rounded">
                Reject
            </button>
        </form>

    </div>

</div>

<!-- DOCUMENT MODAL -->
<div id="docModal"
     class="hidden fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50 p-4">

    <div class="bg-white w-full max-w-5xl h-[90vh] rounded shadow relative flex flex-col">

        <!-- CLOSE BUTTON -->
        <button onclick="closeDocModal()"
                class="absolute top-2 right-2 text-gray-600 z-10">
            ✖
        </button>

        <!-- VIEWER -->
        <div class="flex-1 overflow-hidden">

            <!-- IMAGE VIEW -->
            <img id="docImage"
                 class="hidden w-full h-full object-contain bg-gray-100"
                 src="">

            <!-- PDF VIEW -->
            <iframe id="docFrame"
                    class="hidden w-full h-full"
                    src=""></iframe>

        </div>

    </div>

</div>
<script>
function openDocModal(url) {

    const image = document.getElementById('docImage');
    const frame = document.getElementById('docFrame');

    const extension = url.split('.').pop().toLowerCase();

    // reset
    image.classList.add('hidden');
    frame.classList.add('hidden');

    if (['jpg', 'jpeg', 'png', 'webp'].includes(extension)) {
        image.src = url;
        image.classList.remove('hidden');
    } else {
        frame.src = url;
        frame.classList.remove('hidden');
    }

    document.getElementById('docModal').classList.remove('hidden');
}

function closeDocModal() {
    document.getElementById('docModal').classList.add('hidden');
    document.getElementById('docFrame').src = '';
    document.getElementById('docImage').src = '';
}
</script>
<script>
function updateDocStatus(docId, status) {

    fetch(`/admin/documents/${docId}/${status}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {

        // update badge
        let badge = document.getElementById(`status-${docId}`);

        badge.innerText = data.status.charAt(0).toUpperCase() + data.status.slice(1);

        badge.className = "text-xs px-2 py-1 rounded " +
            (data.status === 'approved'
                ? 'bg-green-100 text-green-600'
                : data.status === 'rejected'
                    ? 'bg-red-100 text-red-600'
                    : 'bg-yellow-100 text-yellow-600'
            );

        showToast(data.message);

    })
    .catch(() => {
        showToast("Something went wrong", "error");
    });
}
</script>
<script>
function showToast(message, type = "success") {

    const toast = document.getElementById('toast');

    toast.innerText = message;
    toast.classList.remove('hidden');

    toast.className = "fixed top-5 right-5 px-4 py-2 rounded shadow-lg z-50 text-white " +
        (type === "success" ? "bg-green-600" : "bg-red-600");

    setTimeout(() => {
        toast.classList.add('hidden');
    }, 3000);
}
</script>
@endsection