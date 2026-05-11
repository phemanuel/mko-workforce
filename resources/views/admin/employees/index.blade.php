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

<!-- ========================= -->
<!-- EDIT EMPLOYEE MODAL -->
<!-- ========================= -->
<div id="editModal"
     class="fixed inset-0 bg-black/50 hidden z-50 items-center justify-center p-4">

    <div class="bg-white w-full max-w-7xl h-[92vh] rounded-2xl shadow-2xl overflow-hidden flex flex-col">

        <!-- HEADER -->
        <div class="p-6 border-b bg-white sticky top-0 z-10">

            <div class="flex items-center justify-between">

                <div>
                    <h2 class="text-xl font-bold text-gray-800">
                        Edit Employee Profile
                    </h2>
                    <p class="text-sm text-gray-500">
                        Full HR management system
                    </p>
                </div>

                <button onclick="closeEditModal()" class="text-xl text-gray-500 hover:text-black">
                    ✕
                </button>

            </div>

            <!-- TABS -->
            <div class="flex gap-2 mt-4 flex-wrap">

                <button class="edit-tab px-3 py-1 border rounded text-sm" data-tab="personal">
                    Personal
                </button>

                <button class="edit-tab px-3 py-1 border rounded text-sm" data-tab="employment">
                    Employment
                </button>

                <button class="edit-tab px-3 py-1 border rounded text-sm" data-tab="role">
                    Role
                </button>

                <button class="edit-tab px-3 py-1 border rounded text-sm" data-tab="eligibility">
                    Eligibility
                </button>

                <button class="edit-tab px-3 py-1 border rounded text-sm" data-tab="payroll">
                    Payroll
                </button>

                <button class="edit-tab px-3 py-1 border rounded text-sm" data-tab="documents">
                    Documents
                </button>

            </div>

        </div>

        <!-- BODY -->
        <form id="editEmployeeForm" class="flex-1 overflow-y-auto p-6 space-y-10">

            <input type="hidden" id="edit_employee_id">

            <!-- ================= PERSONAL ================= -->
            <div id="tab-personal" class="edit-section space-y-4">

                <h3 class="font-semibold text-lg">👤 Personal Details</h3>

                <div class="grid grid-cols-3 gap-4">

                    <div>
                        <label>Full Name</label>
                        <input id="edit_name" class="w-full border p-2 rounded">
                    </div>

                    <!-- <div>
                        <label>Email</label>
                        <input id="edit_email" class="w-full border p-2 rounded">
                    </div> -->

                    <div>
                        <label>Phone</label>
                        <input id="edit_phone" class="w-full border p-2 rounded">
                    </div>

                    <div>
                        <label>Gender</label>
                        <select id="edit_gender" class="w-full border p-2 rounded">

                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>

                        </select>
                    </div>

                    <div>
                        <label>Date of Birth</label>
                        <input id="edit_date_of_birth" type="date" class="w-full border p-2 rounded">
                    </div>

                    <div>
                        <label>Nationality</label>
                        <select id="edit_nationality" class="w-full border p-2 rounded">

                            <option value="">Select Nationality</option>

                            <option value="Afghanistan">Afghanistan</option>
                            <option value="Albania">Albania</option>
                            <option value="Algeria">Algeria</option>
                            <option value="Andorra">Andorra</option>
                            <option value="Angola">Angola</option>
                            <option value="Argentina">Argentina</option>
                            <option value="Armenia">Armenia</option>
                            <option value="Australia">Australia</option>
                            <option value="Austria">Austria</option>
                            <option value="Azerbaijan">Azerbaijan</option>

                            <option value="Bangladesh">Bangladesh</option>
                            <option value="Belgium">Belgium</option>
                            <option value="Benin">Benin</option>
                            <option value="Brazil">Brazil</option>
                            <option value="Bulgaria">Bulgaria</option>

                            <option value="Cameroon">Cameroon</option>
                            <option value="Canada">Canada</option>
                            <option value="China">China</option>
                            <option value="Colombia">Colombia</option>
                            <option value="Congo">Congo</option>

                            <option value="Denmark">Denmark</option>
                            <option value="Egypt">Egypt</option>
                            <option value="Ethiopia">Ethiopia</option>

                            <option value="France">France</option>
                            <option value="Finland">Finland</option>

                            <option value="Germany">Germany</option>
                            <option value="Ghana">Ghana</option>
                            <option value="Greece">Greece</option>

                            <option value="India">India</option>
                            <option value="Indonesia">Indonesia</option>
                            <option value="Ireland">Ireland</option>
                            <option value="Israel">Israel</option>
                            <option value="Italy">Italy</option>

                            <option value="Japan">Japan</option>
                            <option value="Jordan">Jordan</option>

                            <option value="Kenya">Kenya</option>

                            <option value="Lebanon">Lebanon</option>
                            <option value="Liberia">Liberia</option>
                            <option value="Libya">Libya</option>

                            <option value="Malaysia">Malaysia</option>
                            <option value="Mexico">Mexico</option>
                            <option value="Morocco">Morocco</option>

                            <option value="Netherlands">Netherlands</option>
                            <option value="New Zealand">New Zealand</option>
                            <option value="Nigeria">Nigeria</option>
                            <option value="Norway">Norway</option>

                            <option value="Pakistan">Pakistan</option>
                            <option value="Philippines">Philippines</option>
                            <option value="Poland">Poland</option>
                            <option value="Portugal">Portugal</option>

                            <option value="Qatar">Qatar</option>

                            <option value="Romania">Romania</option>
                            <option value="Russia">Russia</option>

                            <option value="Saudi Arabia">Saudi Arabia</option>
                            <option value="Senegal">Senegal</option>
                            <option value="Singapore">Singapore</option>
                            <option value="South Africa">South Africa</option>
                            <option value="South Korea">South Korea</option>
                            <option value="Spain">Spain</option>
                            <option value="Sri Lanka">Sri Lanka</option>
                            <option value="Sweden">Sweden</option>
                            <option value="Switzerland">Switzerland</option>

                            <option value="Tanzania">Tanzania</option>
                            <option value="Thailand">Thailand</option>
                            <option value="Turkey">Turkey</option>

                            <option value="Uganda">Uganda</option>
                            <option value="Ukraine">Ukraine</option>
                            <option value="United Arab Emirates">United Arab Emirates</option>
                            <option value="United Kingdom">United Kingdom</option>
                            <option value="United States">United States</option>

                            <option value="Venezuela">Venezuela</option>
                            <option value="Vietnam">Vietnam</option>

                            <option value="Yemen">Yemen</option>
                            <option value="Zambia">Zambia</option>
                            <option value="Zimbabwe">Zimbabwe</option>

                        </select>
                    </div>

                    <div>
                        <label>NI Number</label>
                        <input id="edit_ni_number" class="w-full border p-2 rounded">
                    </div>

                    <div>
                        <label>Postcode</label>
                        <input id="edit_postcode" class="w-full border p-2 rounded">
                    </div>

                </div>

                <div>
                    <label>Address</label>
                    <textarea id="edit_address" class="w-full border p-2 rounded"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">

                    <div>
                        <label>Emergency Contact Name</label>
                        <input id="edit_emergency_name" class="w-full border p-2 rounded">
                    </div>

                    <div>
                        <label>Emergency Contact Phone</label>
                        <input id="edit_emergency_phone" class="w-full border p-2 rounded">
                    </div>

                </div>

            </div>

            <!-- ================= EMPLOYMENT ================= -->
            <div id="tab-employment" class="edit-section hidden space-y-4">

                <h3 class="font-semibold text-lg">💼 Employment</h3>

                <div class="grid grid-cols-3 gap-4">

                    <div>
                        <label class="text-sm font-medium">Primary Role</label>

                            <select id="edit_primary_role" class="w-full border p-2 rounded">

                                <option value="">Select Role</option>
                                <option value="SIA Security">SIA Security</option>
                                <option value="Cleaning Services">Cleaning Services</option>
                                <option value="Companion Support">Companion Support</option>
                                <option value="Back Support Services">Back Support Services</option>

                            </select>
                    </div>

                    <div>
                        <label class="text-sm font-medium">Employment Type</label>

                        <select id="edit_employment_type" class="w-full border p-2 rounded">

                            <option value="">Select</option>
                            <option value="Full-Time">Full-Time</option>
                            <option value="Part-Time">Part-Time</option>
                            <option value="Casual">Casual</option>

                        </select>
                    </div>

                    <div>
                        <label>Start Date</label>
                        <input id="edit_start_date" type="date" class="w-full border p-2 rounded">
                    </div>

                </div>

            </div>

            <!-- ================= ROLE ================= -->
            <!-- ================= ROLE ================= -->
            <div id="tab-role" class="edit-section hidden space-y-6">

                <h3 class="font-semibold text-lg">⭐ Role Details</h3>

                <!-- ROLE BASIC INFO -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- ================= PRIMARY ROLE (READ ONLY) ================= -->
<div class="flex flex-col gap-1">

    <label class="text-sm font-medium text-gray-700">
        Primary Role
    </label>

    <input id="edit_role_type"
           class="w-full border p-2 rounded bg-gray-100 text-gray-700"
           readonly
           disabled>

</div>
                    
                    <!-- SECONDARY SKILLS -->
                    <div class="col-span-2">

                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            Secondary Skills
                        </label>

                        <div id="edit_secondary_skills_container"
                            class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        </div>

                    </div>

                </div>

                <!-- ROLE DYNAMIC FIELDS -->
                <div class="border-t pt-6">

                    <h4 class="font-semibold text-gray-800 mb-4">
                        Role Specific Details
                    </h4>

                    <!-- GRID FIX FOR DYNAMIC FIELDS -->
                    <div id="role_dynamic_container"
                        class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    </div>

                </div>

            </div>

            <!-- ================= ELIGIBILITY ================= -->
            <div id="tab-eligibility" class="edit-section hidden space-y-4">

                <h3 class="font-semibold text-lg">🛂 Work Eligibility</h3>

                <div class="grid grid-cols-3 gap-4">

                    <div>
                        <!-- ================= WORK ELIGIBILITY - WORK STATUS ================= -->
                        <div class="flex flex-col gap-1">

                            <label class="text-sm font-medium text-gray-700">
                                Right To Work Status
                            </label>

                            <select id="edit_work_status"
                                    class="w-full border rounded-lg p-3 mt-1">

                                <option value="">Select</option>
                                <option value="UK Citizen">UK Citizen</option>
                                <option value="BRP">BRP</option>
                                <option value="Visa">Visa</option>

                            </select>

                        </div>
                    </div>

                    <div>
                        <label>Share Code</label>
                        <input id="edit_share_code" class="w-full border p-2 rounded">
                    </div>

                    <div>
                        <label>Expiry Date</label>
                        <input id="edit_eligibility_expiry" type="date" class="w-full border p-2 rounded">
                    </div>

                </div>

            </div>

            <!-- ================= PAYROLL ================= -->
            <div id="tab-payroll" class="edit-section hidden space-y-4">

                <h3 class="font-semibold text-lg">💰 Payroll</h3>

                <div class="grid grid-cols-3 gap-4">

                    <div>
                        <label>Bank Name</label>
                        <input id="edit_bank_name" class="w-full border p-2 rounded">
                    </div>

                    <div>
                        <label>Account Number</label>
                        <input id="edit_account_number" class="w-full border p-2 rounded">
                    </div>

                    <div>
                        <label>Sort Code</label>
                        <input id="edit_sort_code" class="w-full border p-2 rounded">
                    </div>

                    <div>
                        <label>UTR</label>
                        <input id="edit_utr" class="w-full border p-2 rounded">
                    </div>

                    <div>
                        <!-- ================= PAYMENT TYPE ================= -->
                        <div class="flex flex-col gap-1">

                            <label class="text-sm font-medium text-gray-700">
                                Payment Type
                            </label>

                            <select id="edit_payment_type"
                                    class="w-full border rounded-lg p-3 mt-1">

                                <option value="">Select Payment Type</option>
                                <option value="PAYE">PAYE</option>
                                <option value="Self-Employed">Self-Employed</option>

                            </select>

                        </div>
                    </div>

                </div>

            </div>

            <!-- ================= DOCUMENTS ================= -->
            <div id="tab-documents" class="edit-section hidden space-y-4">

                <h3 class="font-semibold text-lg">📄 Documents</h3>

                <p class="text-sm text-gray-500">
                    Document editing handled in profile view (preview only here)
                </p>

                <div id="edit_documents_list"></div>

            </div>

            <!-- ACTIONS -->
            <div class="flex justify-end gap-3 border-t pt-6">

                <button type="button"
                        onclick="closeEditModal()"
                        class="px-4 py-2 border rounded">

                    Cancel

                </button>

                <button type="submit"
                        class="px-6 py-2 bg-black text-white rounded">

                    Save Changes

                </button>

            </div>

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

            ${
    (() => {

        const passport = employee.documents?.find(doc =>
            doc.document_type?.toLowerCase() === 'passport'
        );

        const passportUrl = passport
            ? `/storage/${passport.file_path}`
            : `https://ui-avatars.com/api/?name=${encodeURIComponent(employee.user?.name ?? 'Employee')}`;

        return `
            <img
                src="${passportUrl}"
                class="w-28 h-28 rounded-3xl object-cover border border-gray-200 shadow-sm"
            >
        `;
    })()
}

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
                        ${employee.approval_status ?? 'active'}
                    </span>
                </div>

            </div>

        </div>

        <!-- WORK ELIGIBILITY -->
        <div class="bg-white border rounded-2xl p-6">

            <div class="flex items-center gap-3 mb-5">

                <div class="w-10 h-10 rounded-xl bg-green-600 text-white flex items-center justify-center">
                    🛂
                </div>

                <div>
                    <h3 class="font-semibold text-gray-800">
                        Work Eligibility
                    </h3>

                    <p class="text-xs text-gray-500">
                        Right-to-work and visa status
                    </p>
                </div>

            </div>

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

            <div class="flex items-center gap-3 mb-5">

                <div class="w-10 h-10 rounded-xl bg-purple-600 text-white flex items-center justify-center">
                    🧭
                </div>

                <div>
                    <h3 class="font-semibold text-gray-800">
                        Role Details
                    </h3>

                    <p class="text-xs text-gray-500">
                        Position, skills and role configuration
                    </p>
                </div>

            </div>

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

            <div class="flex items-center gap-3 mb-5">

                <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center">
                    💳
                </div>

                <div>
                    <h3 class="font-semibold text-gray-800">
                        Payroll
                    </h3>

                    <p class="text-xs text-gray-500">
                        Payment and banking details
                    </p>
                </div>

            </div>          

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

        <div class="flex items-center gap-3 mb-5">

            <div class="w-10 h-10 rounded-xl bg-red-600 text-white flex items-center justify-center">
                📄
            </div>

            <div>
                <h3 class="font-semibold text-gray-800">
                    Compliance Documents
                </h3>

                <p class="text-xs text-gray-500">
                    Identity and regulatory verification files
                </p>
            </div>

        </div>

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

let CURRENT_EMPLOYEE = null;

/*
|--------------------------------------------------------------------------
| HELPERS
|--------------------------------------------------------------------------
*/
function safeJSONParse(value, fallback = {}) {
    try {
        return typeof value === 'string' ? JSON.parse(value) : (value ?? fallback);
    } catch (e) {
        return fallback;
    }
}

function setValue(id, value) {
    const el = document.getElementById(id);
    if (el) el.value = value ?? '';
}

/*
|--------------------------------------------------------------------------
| ROLE DYNAMIC EDIT RENDERER (data1)
|--------------------------------------------------------------------------
*/
function renderField(label, input) {
    return `
        <div class="flex flex-col gap-1">
            <label class="text-sm font-medium text-gray-700">
                ${label}
            </label>
            ${input}
        </div>
    `;
}

function renderRoleEditFields(roleType, data1 = {})
{
    const container = document.getElementById('role_dynamic_container');
    if (!container) return;

    const data = safeJSONParse(data1);

    let html = '';

    switch (roleType) {

        case 'Cleaning Services':
            html = `
                ${renderField(
                    'Experience Years',
                    `<input class="border p-2 rounded w-full"
                        name="role_data1[experience_years]"
                        value="${data.experience_years ?? ''}">`
                )}

                ${renderField(
                    'Equipment Knowledge',
                    `<select class="border p-2 rounded w-full"
                        name="role_data1[equipment_knowledge]">
                        <option value="">Select</option>
                        <option value="Yes" ${data.equipment_knowledge === 'Yes' ? 'selected' : ''}>Yes</option>
                        <option value="No" ${data.equipment_knowledge === 'No' ? 'selected' : ''}>No</option>
                    </select>`
                )}

                ${renderField(
                    'COSHH Awareness',
                    `<select class="border p-2 rounded w-full"
                        name="role_data1[coshh]">
                        <option value="">Select</option>
                        <option value="Yes" ${data.coshh === 'Yes' ? 'selected' : ''}>Yes</option>
                        <option value="No" ${data.coshh === 'No' ? 'selected' : ''}>No</option>
                    </select>`
                )}
            `;
            break;

        case 'SIA Security':
            html = `
                ${renderField(
                    'Licence Number',
                    `<input class="border p-2 rounded w-full"
                        name="role_data1[sia_licence_number]"
                        value="${data.sia_licence_number ?? ''}">`
                )}

                ${renderField(
                    'Licence Type',
                    `<input class="border p-2 rounded w-full"
                        name="role_data1[licence_type]"
                        value="${data.licence_type ?? ''}">`
                )}

                ${renderField(
                    'Expiry Date',
                    `<input type="date" class="border p-2 rounded w-full"
                        name="role_data1[expiry_date]"
                        value="${data.expiry_date ?? ''}">`
                )}
            `;
            break;

        case 'Companion Support':
            html = `
                ${renderField(
                    'DBS Status',
                    `<select class="border p-2 rounded w-full"
                        name="role_data1[dbs_status]">
                        <option value="">Select</option>
                        <option value="Clear" ${data.dbs_status === 'Clear' ? 'selected' : ''}>Clear</option>
                        <option value="Pending" ${data.dbs_status === 'Pending' ? 'selected' : ''}>Pending</option>
                    </select>`
                )}

                ${renderField(
                    'Care Experience',
                    `<input class="border p-2 rounded w-full"
                        name="role_data1[care_experience]"
                        value="${data.care_experience ?? ''}">`
                )}
            `;
            break;

        case 'Back Support Services':
            html = `
                ${renderField(
                    'Department Area',
                    `<input class="border p-2 rounded w-full"
                        name="role_data1[area]"
                        value="${data.area ?? ''}">`
                )}

                ${renderField(
                    'Software Skills',
                    `<input class="border p-2 rounded w-full"
                        name="role_data1[software_skills]"
                        value="${data.software_skills ?? ''}">`
                )}
            `;
            break;

        default:
            html = `<p class="text-sm text-gray-500">No role-specific fields</p>`;
    }

    container.innerHTML = html;
}


const ALL_SKILLS = [
    'SIA Licensed',
    'Door Supervisor',
    'CCTV Operator',
    'Cleaning Commercial',
    'Cleaning Residential',
    'Care Support Work',
    'Admin Support',
    'Logistics Support',
];

/*
|--------------------------------------------------------------------------
| RENDER SECONDARY SKILLS CHECKBOXES
|--------------------------------------------------------------------------
*/
function renderSecondarySkills(selectedSkills = [])
{
    const container = document.getElementById('edit_secondary_skills_container');
    if (!container) return;

    const selected = Array.isArray(selectedSkills)
        ? selectedSkills
        : [];

    let html = '';

    ALL_SKILLS.forEach(skill => {

        const checked = selected.includes(skill) ? 'checked' : '';

        html += `
            <label class="flex items-center gap-2 border rounded-lg p-3 hover:bg-gray-50 cursor-pointer">

                <input type="checkbox"
                       name="secondary_skills[]"
                       value="${skill}"
                       class="rounded border-gray-300 text-red-600 focus:ring-red-500"
                       ${checked}>

                <span class="text-sm text-gray-700">
                    ${skill}
                </span>

            </label>
        `;
    });

    container.innerHTML = html;
}

/*
|--------------------------------------------------------------------------
| OPEN EDIT MODAL
|--------------------------------------------------------------------------
*/
async function openEditModal(employeeId)
{
    try {

        closeProfileModal();

        const response = await fetch(`/admin/employees/${employeeId}`);

        if (!response.ok) {
            throw new Error('Unable to load employee');
        }

        const employee = await response.json();
        CURRENT_EMPLOYEE = employee;

        console.log('EDIT EMPLOYEE:', employee);

        const modal = document.getElementById('editModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        /*
        |--------------------------------------------------------------------------
        | NORMALISE RELATIONS
        |--------------------------------------------------------------------------
        */
        const profile = employee.profile ?? {};
        const role = employee.role_detail ?? employee.roleDetail ?? {};
        const payroll = employee.payroll ?? {};
        const work = employee.work_eligibility ?? employee.workEligibility ?? {};
        renderSecondarySkills(employee.role_detail?.secondary_skills ?? []);

        /*
        |--------------------------------------------------------------------------
        | CORE
        |--------------------------------------------------------------------------
        */
        setValue('edit_employee_id', employee.id);
        setValue('edit_name', employee.user?.name);

        setValue('edit_primary_role', employee.primary_role);
        setValue('edit_employment_type', employee.employment_type);
        setValue('edit_start_date', employee.start_date);

        /*
        |--------------------------------------------------------------------------
        | PROFILE
        |--------------------------------------------------------------------------
        */
        setValue('edit_phone', profile.phone);
        setValue('edit_gender', profile.gender);
        setValue('edit_date_of_birth', profile.date_of_birth);
        setValue('edit_nationality', profile.nationality);
        setValue('edit_address', profile.address);
        setValue('edit_postcode', profile.postcode);
        setValue('edit_ni_number', profile.ni_number);
        setValue('edit_emergency_name', profile.emergency_contact_name);
        setValue('edit_emergency_phone', profile.emergency_contact_phone);

        /*
        |--------------------------------------------------------------------------
        | WORK ELIGIBILITY
        |--------------------------------------------------------------------------
        */
        setValue('edit_work_status', work.work_status);
        setValue('edit_share_code', work.share_code);
        setValue('edit_eligibility_expiry', work.expiry_date);

        /*
        |--------------------------------------------------------------------------
        | ROLE DETAILS (STATIC FIELDS)
        |--------------------------------------------------------------------------
        */
        setValue('edit_role_type', role.role_type);        

        setValue(
            'edit_role_data',
            JSON.stringify(role.data ?? {}, null, 2)
        );

        setValue(
            'edit_role_data1',
            JSON.stringify(role.data1 ?? {}, null, 2)
        );

        /*
        |--------------------------------------------------------------------------
        | ROLE DYNAMIC EDIT UI (IMPORTANT FIX)
        |--------------------------------------------------------------------------
        */
        renderRoleEditFields(employee.primary_role, role.data1 ?? {});

        /*
        |--------------------------------------------------------------------------
        | PAYROLL
        |--------------------------------------------------------------------------
        */
        setValue('edit_bank_name', payroll.bank_name);
        setValue('edit_account_number', payroll.account_number);
        setValue('edit_sort_code', payroll.sort_code);
        setValue('edit_utr', payroll.utr);
        setValue('edit_payment_type', payroll.payment_type);

    }
    catch (error) {
        console.error(error);
        alert('Unable to load employee profile');
    }
}

/*
|--------------------------------------------------------------------------
| CLOSE MODAL
|--------------------------------------------------------------------------
*/
function closeEditModal()
{
    const modal = document.getElementById('editModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

/*
|--------------------------------------------------------------------------
| SUBMIT UPDATE
|--------------------------------------------------------------------------
*/
document.getElementById('editEmployeeForm')
.addEventListener('submit', async function (e) {

    e.preventDefault();

    const get = (id) => document.getElementById(id)?.value ?? '';

    const id = get('edit_employee_id');

    const payload = {
        name: get('edit_name'),        

        phone: get('edit_phone'),
        gender: get('edit_gender'),
        date_of_birth: get('edit_date_of_birth'),

        primary_role: get('edit_primary_role'),
        employment_type: get('edit_employment_type'),
        start_date: get('edit_start_date'),

        nationality: get('edit_nationality'),
        address: get('edit_address'),
        postcode: get('edit_postcode'),
        ni_number: get('edit_ni_number'),

        emergency_contact_name: get('edit_emergency_name'),
        emergency_contact_phone: get('edit_emergency_phone'),

        work_status: get('edit_work_status'),
        share_code: get('edit_share_code'),
        expiry_date: get('edit_eligibility_expiry'),

        secondary_skills: Array.from(
            document.querySelectorAll('input[name="secondary_skills[]"]:checked')
        ).map(el => el.value),

        bank_name: get('edit_bank_name'),
        account_number: get('edit_account_number'),
        sort_code: get('edit_sort_code'),
        utr: get('edit_utr'),
        payment_type: get('edit_payment_type'),
    };

    console.log('PAYLOAD:', payload);

    try {

    const response = await fetch(`/admin/employees/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(payload)
    });

    const data = await response.json();

    /*
    |--------------------------------------------------------------------------
    | SUCCESS
    |--------------------------------------------------------------------------
    */
    if (data.success) {

        alert(data.message);

        closeEditModal();

        location.reload();

    } else {

        alert(data.message || 'Update failed');

    }

} catch (error) {

    console.error(error);

    alert('Something went wrong while updating employee');

}
});
</script>

<script>
    document.querySelectorAll('.edit-tab').forEach(btn => {

    btn.addEventListener('click', function () {

        const tab = this.dataset.tab;

        document.querySelectorAll('.edit-section').forEach(el => {
            el.classList.add('hidden');
        });

        document.getElementById('tab-' + tab).classList.remove('hidden');
    });

});
</script>
<script>
    function renderRoleFields(role, roleData = {}) {

    const container = document.getElementById('roleFields');

    let html = '';

    // =========================
    // SIA SECURITY
    // =========================
    if (role === 'SIA Security') {

        html = `
            <div class="mb-3">
                <label class="text-sm font-medium">SIA Licence Number</label>
                <input id="sia_licence_number" class="w-full border p-2 rounded"
                    value="${roleData.sia_licence_number ?? ''}">
            </div>

            <div class="mb-3">
                <label class="text-sm font-medium">Licence Type</label>
                <input id="licence_type" class="w-full border p-2 rounded"
                    value="${roleData.licence_type ?? ''}">
            </div>

            <div class="mb-3">
                <label class="text-sm font-medium">Licence Expiry Date</label>
                <input id="licence_expiry" type="date" class="w-full border p-2 rounded"
                    value="${roleData.expiry_date ?? ''}">
            </div>

            <div class="mb-3">
                <label class="text-sm font-medium">First Aid Certified?</label>
                <select id="first_aid" class="w-full border p-2 rounded">
                    <option value="">Select</option>
                    <option value="Yes" ${roleData.first_aid === 'Yes' ? 'selected' : ''}>Yes</option>
                    <option value="No" ${roleData.first_aid === 'No' ? 'selected' : ''}>No</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="text-sm font-medium">First Aid Expiry</label>
                <input id="first_aid_expiry" type="date" class="w-full border p-2 rounded"
                    value="${roleData.first_aid_expiry ?? ''}">
            </div>
        `;
    }

    // =========================
    // CLEANING SERVICES
    // =========================
    else if (role === 'Cleaning Services') {

        html = `
            <div class="mb-3">
                <label class="text-sm font-medium">Years of Experience</label>
                <input id="experience_years" type="number" class="w-full border p-2 rounded"
                    value="${roleData.experience_years ?? ''}">
            </div>

            <div class="mb-3">
                <label class="text-sm font-medium">Equipment Knowledge</label>
                <select id="equipment_knowledge" class="w-full border p-2 rounded">
                    <option value="">Select</option>
                    <option value="Yes" ${roleData.equipment_knowledge === 'Yes' ? 'selected' : ''}>Yes</option>
                    <option value="No" ${roleData.equipment_knowledge === 'No' ? 'selected' : ''}>No</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="text-sm font-medium">COSHH Awareness</label>
                <select id="coshh" class="w-full border p-2 rounded">
                    <option value="">Select</option>
                    <option value="Yes" ${roleData.coshh === 'Yes' ? 'selected' : ''}>Yes</option>
                    <option value="No" ${roleData.coshh === 'No' ? 'selected' : ''}>No</option>
                </select>
            </div>
        `;
    }

    // =========================
    // COMPANION SUPPORT
    // =========================
    else if (role === 'Companion Support') {

        html = `
            <div class="mb-3">
                <label class="text-sm font-medium">DBS Status</label>
                <select id="dbs_status" class="w-full border p-2 rounded">
                    <option value="">Select</option>
                    <option value="Clear" ${roleData.dbs_status === 'Clear' ? 'selected' : ''}>Clear</option>
                    <option value="Pending" ${roleData.dbs_status === 'Pending' ? 'selected' : ''}>Pending</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="text-sm font-medium">DBS Number</label>
                <input id="dbs_number" class="w-full border p-2 rounded"
                    value="${roleData.dbs_number ?? ''}">
            </div>

            <div class="mb-3">
                <label class="text-sm font-medium">Care Experience</label>
                <input id="care_experience" class="w-full border p-2 rounded"
                    value="${roleData.care_experience ?? ''}">
            </div>
        `;
    }

    // =========================
    // BACK SUPPORT
    // =========================
    else if (role === 'Back Support Services') {

        html = `
            <div class="mb-3">
                <label class="text-sm font-medium">Department Area</label>
                <select id="area" class="w-full border p-2 rounded">
                    <option value="">Select</option>
                    <option value="Admin" ${roleData.area === 'Admin' ? 'selected' : ''}>Admin</option>
                    <option value="Logistics" ${roleData.area === 'Logistics' ? 'selected' : ''}>Logistics</option>
                    <option value="Operations" ${roleData.area === 'Operations' ? 'selected' : ''}>Operations</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="text-sm font-medium">Software Skills</label>
                <input id="software_skills" class="w-full border p-2 rounded"
                    value="${roleData.software_skills ?? ''}">
            </div>

            <div class="mb-3">
                <label class="text-sm font-medium">Experience</label>
                <textarea id="experience" class="w-full border p-2 rounded">
                    ${roleData.experience ?? ''}
                </textarea>
            </div>
        `;
    }

    container.innerHTML = html;
}
</script>
@endsection