@extends('layouts.admin')

@section('page-title', 'Employees')

@section('content')

<div class="bg-white rounded-2xl shadow-sm border border-gray-100">

    <!-- HEADER -->
    <div class="flex items-center justify-between px-6 py-5 border-b">

        <div>
            <h3 class="text-lg font-semibold text-gray-800">Employees</h3>
            <p class="text-sm text-gray-500">Workforce directory & management</p>
        </div>

        <div class="flex gap-3 items-center">

            <form method="GET" class="flex">

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search employees..."
                    class="border rounded-l-lg px-3 py-2 text-sm w-64">

                <button class="bg-black text-white px-3 rounded-r-lg text-sm">
                    Search
                </button>

            </form>

            <a href="/admin/employees-export/csv"
            class="px-4 py-2 bg-gray-900 text-white rounded-lg text-sm">
                Export
            </a>

        </div>

    </div>

    <!-- TABLE -->
    <div class="overflow-x-auto">

        <table class="w-full text-sm">

            <thead class="bg-gray-50 text-gray-600 text-left">

                <tr>
                    <th class="p-4">Employee</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Date Joined</th>
                    <th class="text-right p-4">Action</th>
                </tr>

            </thead>

            <tbody>

                @foreach($employees as $employee)

                    @php

                        $passport = $employee->documents
                            ->first(function ($doc) {
                                return strtolower($doc->document_type) === 'passport';
                            });

                        $passportUrl = $passport
                            ? asset('storage/' . ltrim($passport->file_path, '/'))
                            : 'https://ui-avatars.com/api/?name=' . urlencode($employee->user->name);

                    @endphp
                    

                    <tr class="border-t hover:bg-gray-50">

                        <td class="p-4 flex items-center gap-3">                            
                            <img src="{{ $passportUrl }}"
     class="w-10 h-10 rounded-full object-cover border border-gray-200">

                            <div>
                                <p class="font-medium text-gray-800">
                                    {{ $employee->user->name }}
                                </p>

                                <p class="text-xs text-gray-500">
                                    ID: {{ $employee->id }}
                                </p>
                            </div>

                        </td>

                        <td>{{ $employee->user->email }}</td>

                        <td>{{ $employee->primary_role ?? 'N/A' }}</td>

                        <td>
                            <span class="px-2 py-1 text-xs rounded-full
                                {{ $employee->user->status == 'active'
                                    ? 'bg-green-100 text-green-600'
                                    : 'bg-yellow-100 text-yellow-600' }}">
                                {{ $employee->user->status }}
                            </span>
                        </td>

                        <td>
                            {{ $employee->created_at->format('M d, Y') }}
                        </td>

                        <td class="text-right p-4">

                            <div class="flex justify-end">

    <button
    onclick="openProfile({{ $employee->id }})"
    class="group flex items-center gap-2 px-3 py-2 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 transition">

    <div class="w-7 h-7 rounded-lg bg-gray-100 flex items-center justify-center">
        👁
    </div>

    <div class="text-left">
        <p class="text-xs font-semibold text-gray-700">
            Open
        </p>
        <p class="text-[10px] text-gray-400">
            Profile
        </p>
    </div>

</button>

</div>

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>



<!-- EMPLOYEE DRAWER -->
<div id="profileModal"
     class="fixed inset-0 z-50 hidden">

    <!-- BACKDROP -->
    <div class="absolute inset-0 bg-black/50"
         onclick="closeProfileModal()"></div>

    <!-- DRAWER -->
    <div class="absolute right-0 top-0 h-full w-full sm:w-[85%] lg:w-[70%] xl:w-[60%]
                bg-white shadow-2xl flex flex-col">

        <!-- HEADER -->
        <div class="flex items-center justify-between px-8 py-5 border-b bg-white">

            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    Employee Profile
                </h2>
                <p class="text-sm text-gray-500">
                    Full workforce record
                </p>
            </div>

            <button onclick="closeProfileModal()"
                    class="w-10 h-10 flex items-center justify-center rounded-xl hover:bg-gray-100">
                ✕
            </button>

        </div>

        <!-- CONTENT -->
        <div id="profileContent"
             class="flex-1 overflow-y-auto bg-gray-50 p-8">

        </div>

    </div>

</div>

<!-- EDIT MODAL -->
<div id="editModal"
     class="fixed inset-0 bg-black/50 hidden z-50 items-center justify-center p-4">

    <div class="bg-white w-full max-w-2xl rounded-2xl shadow-xl">

        <div class="flex items-center justify-between p-6 border-b">

            <h2 class="text-lg font-bold">
                Edit Employee
            </h2>

            <button onclick="closeEditModal()">
                ✕
            </button>

        </div>

        <form id="editEmployeeForm" class="p-6 space-y-4">

            @csrf

            <input type="hidden" id="edit_employee_id">

            <div>
                <label class="text-sm font-medium">Name</label>

                <input type="text"
                       id="edit_name"
                       class="input mt-1">
            </div>

            <div>
                <label class="text-sm font-medium">Email</label>

                <input type="email"
                       id="edit_email"
                       class="input mt-1">
            </div>

            <div>
                <label class="text-sm font-medium">Phone</label>

                <input type="text"
                       id="edit_phone"
                       class="input mt-1">
            </div>

            <div>
                <label class="text-sm font-medium">Primary Role</label>

                <input type="text"
                       id="edit_primary_role"
                       class="input mt-1">
            </div>

            <button
                type="submit"
                class="bg-black text-white px-5 py-2 rounded-xl">
                Update Employee
            </button>

        </form>

    </div>

</div>

<script>

function openProfile(id) {

    document.getElementById('profileModal').classList.remove('hidden');

    fetch('/admin/employees/' + id + '/data')
        .then(res => res.json())
        .then(data => {

            document.getElementById('profileContent').innerHTML = `
                <p><b>Name:</b> ${data.user.name}</p>
                <p><b>Email:</b> ${data.user.email}</p>
                <p><b>Role:</b> ${data.primary_role ?? 'N/A'}</p>
            `;
        });
}

function closeModal() {
    document.getElementById('profileModal').classList.add('hidden');
}

</script>
<script>

async function openProfile(employeeId)
{
    const modal = document.getElementById('profileModal');
    const content = document.getElementById('profileContent');
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');

    content.innerHTML = `
        <div class="text-center py-10">
            Loading profile...
        </div>
    `;

    const response = await fetch(`/admin/employees/${employeeId}`);
    const employee = await response.json();
    console.log(employee.profile);
    content.innerHTML = `

<div class="space-y-8">

    <!-- HERO SECTION -->
    <div class="flex flex-col lg:flex-row lg:items-start gap-6">

        <!-- AVATAR -->
        <div class="flex-shrink-0">

            <img
                src="${employee.passport_url ?? 'https://ui-avatars.com/api/?name=' + employee.user?.name}"
                class="w-28 h-28 rounded-3xl object-cover border border-gray-200 shadow-sm">

        </div>

        <!-- BASIC INFO -->
        <div class="flex-1">

            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                <div>

                    <h2 class="text-3xl font-bold text-gray-800">
                        ${employee.user?.name ?? '-'}
                    </h2>

                    <p class="text-gray-500 mt-1">
                        ${employee.user?.email ?? '-'}
                    </p>

                    <div class="flex flex-wrap items-center gap-2 mt-4">

                        <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-700">
                            ${employee.status ?? 'active'}
                        </span>

                        <span class="px-3 py-1 text-xs rounded-full bg-gray-100 text-gray-700">
                            ${employee.primary_role ?? 'No Role'}
                        </span>

                        <span class="px-3 py-1 text-xs rounded-full bg-blue-100 text-blue-700">
                            ${employee.employment_type ?? 'N/A'}
                        </span>

                    </div>

                </div>

                <!-- ACTION -->
                <div>

                    <button
                        onclick="openEditModal(${employee.id})"
                        class="bg-black text-white px-5 py-3 rounded-2xl hover:bg-gray-800 transition">

                        Edit Employee

                    </button>

                </div>

            </div>

        </div>

    </div>

    <!-- GRID -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

        <!-- PERSONAL INFORMATION -->
        <div class="bg-gray-50 rounded-2xl p-6">

            <div class="flex items-center gap-3 mb-5">

                <div class="w-10 h-10 rounded-xl bg-black text-white flex items-center justify-center">
                    👤
                </div>

                <div>

                    <h3 class="font-semibold text-gray-800">
                        Personal Information
                    </h3>

                    <p class="text-xs text-gray-500">
                        Core identity and contact details
                    </p>

                </div>

            </div>

            <div class="space-y-4 text-sm">

                <div class="flex justify-between">
                    <span class="text-gray-500">Full Name</span>
                    <span class="font-medium text-gray-800">
                        ${employee.user?.name ?? '-'}
                    </span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-500">Email Address</span>
                    <span class="font-medium text-gray-800">
                        ${employee.user?.email ?? '-'}
                    </span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-500">Phone</span>
                    <span class="font-medium text-gray-800">
                        ${employee.profile?.phone ?? '-'}
                    </span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-500">Gender</span>
                    <span class="font-medium text-gray-800">
                        ${employee.profile?.gender ?? '-'}
                    </span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-500">DOB</span>
                    <span class="font-medium text-gray-800">
                        ${employee.profile?.date_of_birth ?? '-'}
                    </span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-500">Nationality</span>
                    <span class="font-medium text-gray-800">
                        ${employee.profile?.nationality ?? '-'}
                    </span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-500">NI Number</span>
                    <span class="font-medium text-gray-800">
                        ${employee.profile?.ni_number ?? '-'}
                    </span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-500">Address</span>
                    <span class="font-medium text-gray-800 text-right">
                        ${employee.profile?.address ?? '-'}
                    </span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-500">Postcode</span>
                    <span class="font-medium text-gray-800">
                        ${employee.profile?.postcode ?? '-'}
                    </span>
                </div>

            </div>

            <!-- EMERGENCY CONTACT -->
            <div class="mt-6 pt-4 border-t">

                <p class="text-sm font-semibold text-gray-700 mb-2">
                    Emergency Contact
                </p>

                <p class="text-sm text-gray-600">
                    ${employee.profile?.emergency_contact_name ?? '-'}
                    (${employee.profile?.emergency_contact_phone ?? '-'})
                </p>

            </div>

        </div>

        <!-- EMPLOYMENT -->
        <div class="bg-gray-50 rounded-2xl p-6">

            <div class="flex items-center gap-3 mb-5">

                <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center">
                    💼
                </div>

                <div>

                    <h3 class="font-semibold text-gray-800">
                        Employment Details
                    </h3>

                    <p class="text-xs text-gray-500">
                        Role and assignment information
                    </p>

                </div>

            </div>

            <div class="space-y-4 text-sm">

                <div class="flex justify-between">
                    <span class="text-gray-500">Primary Role</span>
                    <span class="font-medium text-gray-800">
                        ${employee.primary_role ?? '-'}
                    </span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-500">Employment Type</span>
                    <span class="font-medium text-gray-800">
                        ${employee.employment_type ?? '-'}
                    </span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-500">Start Date</span>
                    <span class="font-medium text-gray-800">
                        ${employee.start_date ?? '-'}
                    </span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-500">Status</span>
                    <span class="font-medium text-green-600">
                        ${employee.status ?? 'active'}
                    </span>
                </div>

            </div>

        </div>

        <!-- WORK ELIGIBILITY -->
        <div class="bg-white border rounded-2xl p-6">

            <h3 class="font-semibold mb-4">Work Eligibility</h3>

            <div class="space-y-3 text-sm">

                <div class="flex justify-between">
                    <span class="text-gray-500">Status</span>
                    <span class="font-medium text-gray-800">
                        ${employee.work_eligibility?.work_status ?? '-'}
                    </span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-500">Share Code</span>
                    <span class="font-medium text-gray-800">
                        ${employee.work_eligibility?.share_code ?? '-'}
                    </span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-500">Expiry</span>
                    <span class="font-medium text-gray-800">
                        ${employee.work_eligibility?.expiry_date ?? '-'}
                    </span>
                </div>

            </div>

        </div>

        <!-- ROLE DETAILS -->
        <div class="bg-white border rounded-2xl p-6">

            <h3 class="font-semibold mb-4">Role Details</h3>

            <!-- PRIMARY ROLE -->
            <p class="text-sm mb-2 text-gray-500">Primary Role</p>
            <p class="text-sm font-medium mb-3">
                ${employee.role_detail?.role_type ?? '-'}
            </p>

            <!-- SECONDARY SKILLS -->
            <p class="text-sm text-gray-500 mb-2">Secondary Skills</p>

            <div class="flex flex-wrap gap-2 mb-4">

                ${
                    (() => {
                        let skills = employee.role_detail?.secondary_skills;

                        if (typeof skills === 'string') {
                            try {
                                skills = JSON.parse(skills);
                            } catch (e) {
                                skills = [];
                            }
                        }

                        return Array.isArray(skills) && skills.length
                            ? skills.map(skill => `
                                <span class="px-2 py-1 text-xs bg-gray-100 rounded-full">
                                    ${skill}
                                </span>
                            `).join('')
                            : `<span class="text-sm text-gray-400">None</span>`;
                    })()
                }

            </div>

            <!-- DATA (JSON) -->
            <p class="text-sm text-gray-500 mb-2">Additional Information</p>

            <div class="space-y-2 mb-4">

                ${
                    (() => {
                        let data = employee.role_detail?.data;

                        if (typeof data === 'string') {
                            try {
                                data = JSON.parse(data);
                            } catch (e) {
                                data = null;
                            }
                        }

                        return data && Object.keys(data).length
                            ? Object.entries(data).map(([key, value]) => `
                                <div class="flex justify-between text-sm border-b pb-1">
                                    <span class="text-gray-500 capitalize">
                                        ${key.replaceAll('_', ' ')}
                                    </span>
                                    <span class="text-gray-800 font-medium">
                                        ${value ?? '-'}
                                    </span>
                                </div>
                            `).join('')
                            : `<p class="text-sm text-gray-400">No additional data</p>`;
                    })()
                }

            </div>

            <!-- DATA1 (JSON) -->
            <p class="text-sm text-gray-500 mb-2">Extended Information</p>

            <div class="space-y-2">

                ${
                    (() => {
                        let data1 = employee.role_detail?.data1;

                        if (typeof data1 === 'string') {
                            try {
                                data1 = JSON.parse(data1);
                            } catch (e) {
                                data1 = null;
                            }
                        }

                        return data1 && Object.keys(data1).length
                            ? Object.entries(data1).map(([key, value]) => `
                                <div class="flex justify-between text-sm border-b pb-1">
                                    <span class="text-gray-500 capitalize">
                                        ${key.replaceAll('_', ' ')}
                                    </span>
                                    <span class="text-gray-800 font-medium">
                                        ${value ?? '-'}
                                    </span>
                                </div>
                            `).join('')
                            : `<p class="text-sm text-gray-400">No extended data</p>`;
                    })()
                }

            </div>

        </div>

        <!-- PAYROLL -->
        <div class="bg-white border rounded-2xl p-6">

            <h3 class="font-semibold mb-4">Payroll</h3>

            <div class="space-y-3 text-sm">

                <div class="flex justify-between">
                    <span class="text-gray-500">Bank</span>
                    <span class="font-medium">
                        ${employee.payroll?.bank_name ?? '-'}
                    </span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-500">Account</span>
                    <span class="font-medium">
                        ${employee.payroll?.account_number ?? '-'}
                    </span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-500">Sort Code</span>
                    <span class="font-medium">
                        ${employee.payroll?.sort_code ?? '-'}
                    </span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-500">Payment Type</span>
                    <span class="font-medium">
                        ${employee.payroll?.payment_type ?? '-'}
                    </span>
                </div>

            </div>

        </div>

    </div>

    <!-- DOCUMENTS -->
    <div class="bg-white border rounded-2xl p-6">

        <h3 class="font-semibold mb-4">Documents</h3>

        <div class="space-y-3">

            ${
                employee.documents?.length
                ? employee.documents.map(doc => `

                    <div class="flex items-center justify-between border rounded-xl p-3">

                        <div>

                            <p class="font-medium text-sm">
                                ${doc.document_type}
                            </p>

                            <p class="text-xs text-gray-500">
                                ${doc.status ?? 'pending'}
                                ${doc.expiry_date ? ' • Expires: ' + doc.expiry_date : ''}
                            </p>

                        </div>

                        <button onclick="openDocModal('/storage/${doc.file_path}')"
                                class="text-xs bg-black text-white px-3 py-1 rounded-lg">
                            Preview
                        </button>

                    </div>

                `).join('')
                : `<p class="text-sm text-gray-500">No documents uploaded</p>`
            }

        </div>

    </div>

</div>

`;

}

function closeProfileModal()
{
    document.getElementById('profileModal').classList.add('hidden');
}

async function openEditModal(employeeId)
{
    closeProfileModal();

    const response = await fetch(`/admin/employees/${employeeId}`);
    const employee = await response.json();

    document.getElementById('editModal').classList.remove('hidden');
    document.getElementById('editModal').classList.add('flex');

    document.getElementById('edit_employee_id').value = employee.id;
    document.getElementById('edit_name').value = employee.user.name ?? '';
    document.getElementById('edit_email').value = employee.user.email ?? '';
    document.getElementById('edit_phone').value = employee.phone ?? '';
    document.getElementById('edit_primary_role').value = employee.primary_role ?? '';
}

function closeEditModal()
{
    document.getElementById('editModal').classList.add('hidden');
}

document.getElementById('editEmployeeForm')
.addEventListener('submit', async function(e) {

    e.preventDefault();

    const id = document.getElementById('edit_employee_id').value;

    const response = await fetch(`/admin/employees/${id}`, {

        method: 'PUT',

        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },

        body: JSON.stringify({
            name: document.getElementById('edit_name').value,
            email: document.getElementById('edit_email').value,
            phone: document.getElementById('edit_phone').value,
            primary_role: document.getElementById('edit_primary_role').value,
        })

    });

    const data = await response.json();

    if(data.success){

        closeEditModal();

        location.reload();

    }
});
</script>
@endsection