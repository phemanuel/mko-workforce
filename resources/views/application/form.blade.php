@extends('layouts.applicant')

@section('page-title', 'Complete Application')

@section('content')

<div class="max-w-5xl mx-auto space-y-6">

    <!-- HEADER -->
    <div class="bg-white p-6 rounded-lg shadow">

        <h2 class="text-2xl font-bold">Employee Application</h2>

        <p class="text-gray-500 text-sm">
            Complete your application to get started.
        </p>

    </div>

    <!-- PROGRESS -->
    <div class="bg-white p-6 rounded-lg shadow">

        <div class="flex justify-between text-sm mb-2">
            <span>Progress</span>
            <span>Step {{ auth()->user()->registration_step }} of 7</span>
        </div>

        <div class="w-full bg-gray-200 h-2 rounded-full">
            <div class="bg-red-600 h-2 rounded-full"
                 style="width: {{ auth()->user()->registration_step * 14 }}%"></div>
        </div>

    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- STEP FORMS -->
    <div class="bg-white p-6 rounded-lg shadow">

        
        {{-- STEP 2 --}}
        @if(auth()->user()->registration_step == 2)
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4 rounded">

            <p class="font-semibold">Important Notice</p>

            <p class="text-sm mt-1">
                Please review all information carefully before submitting.
                Once submitted, this step cannot be edited by you again.
                Any corrections must be requested through our support channels.
            </p>

        </div>
        <h3 class="text-lg font-semibold mb-6">
            Personal Details
        </h3>
     
        <form method="POST" action="{{ route('application.step2') }}">

            @csrf

            {{-- ==========================================================
                PERSONAL INFORMATION
            =========================================================== --}}

            <div>

                <h3 class="text-lg font-semibold text-gray-900">
                    Personal Information
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    Please provide your personal details.
                </p>

            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-5">

                {{-- FIRST NAME --}}
                <div>

                    <label class="text-sm font-medium text-gray-700">
                        First Name
                    </label>

                    <input
                        type="text"
                        name="first_name"
                        value="{{ old('first_name', auth()->user()->first_name) }}"
                        class="w-full border rounded-lg p-3 mt-1"
                    >

                </div>

                {{-- MIDDLE NAME --}}
                <div>

                    <label class="text-sm font-medium text-gray-700">
                        Middle Name
                    </label>

                    <input
                        type="text"
                        name="middle_name"
                        value="{{ old('middle_name', auth()->user()->middle_name) }}"
                        class="w-full border rounded-lg p-3 mt-1"
                    >

                </div>

                {{-- LAST NAME --}}
                <div>

                    <label class="text-sm font-medium text-gray-700">
                        Last Name
                    </label>

                    <input
                        type="text"
                        name="last_name"
                        value="{{ old('last_name', auth()->user()->last_name) }}"
                        class="w-full border rounded-lg p-3 mt-1"
                    >

                </div>

                {{-- DATE OF BIRTH --}}
                <div>

                    <label class="text-sm font-medium text-gray-700">
                        Date of Birth
                    </label>

                    <input
                        type="date"
                        name="date_of_birth"
                        value="{{ old('date_of_birth') }}"
                        class="w-full border rounded-lg p-3 mt-1"
                    >

                </div>

                {{-- GENDER --}}
                <div>

                    <label class="text-sm font-medium text-gray-700">
                        Gender
                    </label>

                    <select
                        name="gender"
                        class="w-full border rounded-lg p-3 mt-1"
                    >

                        <option value="">
                            Select Gender
                        </option>

                        <option
                            value="Male"
                            {{ old('gender') === 'Male' ? 'selected' : '' }}
                        >
                            Male
                        </option>

                        <option
                            value="Female"
                            {{ old('gender') === 'Female' ? 'selected' : '' }}
                        >
                            Female
                        </option>

                    </select>

                </div>

                {{-- NATIONALITY --}}
                <div>

                    <label class="text-sm font-medium text-gray-700">
                        Nationality
                    </label>

                    <select
                        name="nationality"
                        class="w-full border rounded-lg p-3 mt-1"
                    >

                        <option value="">
                            Select Nationality
                        </option>

                        @foreach($countries as $country)

                            <option
                                value="{{ $country }}"
                                {{ old('nationality') == $country ? 'selected' : '' }}
                            >
                                {{ $country }}
                            </option>

                        @endforeach

                    </select>

                </div>

                {{-- NI NUMBER --}}
                <div>

                    <label class="text-sm font-medium text-gray-700">
                        NI Number
                    </label>

                    <input
                        type="text"
                        name="ni_number"
                        value="{{ old('ni_number') }}"
                        class="w-full border rounded-lg p-3 mt-1"
                    >

                </div>

            </div>


            {{-- ==========================================================
                CONTACT INFORMATION
            =========================================================== --}}

            <div class="border-t mt-8 pt-6">

                <h3 class="text-lg font-semibold text-gray-900">
                    Contact Information
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    Your contact details.
                </p>

            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-5">

                {{-- PHONE --}}
                <div>

                    <label class="text-sm font-medium text-gray-700">
                        Phone Number
                    </label>

                    <input
                        type="text"
                        name="phone_no"
                        value="{{ old('phone_no', auth()->user()->phone_no) }}"
                        class="w-full border rounded-lg p-3 mt-1"
                    >

                </div>

                {{-- EMAIL --}}
                <div>

                    <label class="text-sm font-medium text-gray-700">
                        Email Address
                    </label>

                    <input
                        type="email"
                        value="{{ auth()->user()->email }}"
                        disabled
                        class="w-full bg-gray-100 border rounded-lg p-3 mt-1"
                    >

                </div>

            </div>


            {{-- ==========================================================
                HOME ADDRESS
            =========================================================== --}}

            <div class="border-t mt-8 pt-6">

                <h3 class="text-lg font-semibold text-gray-900">
                    Home Address
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    Enter your UK postcode to verify your location, then provide
                    your full home address.
                </p>

            </div>


            <div class="mt-5">

                {{-- ======================================================
                    POSTCODE
                ======================================================= --}}

                <div class="max-w-md">

                    <label
                        for="postcode"
                        class="text-sm font-medium text-gray-700"
                    >
                        Postcode
                    </label>

                    <div class="flex gap-2 mt-1">

                        <input
                            type="text"
                            id="postcode"
                            name="postcode"
                            value="{{ old('postcode') }}"
                            placeholder="e.g. SW1A 2AA"
                            autocomplete="postal-code"
                            maxlength="8"
                            class="flex-1 border rounded-lg p-3 uppercase"
                        >

                        <button
                            type="button"
                            id="findPostcodeBtn"
                            class="px-5 py-3 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-medium transition"
                        >
                            Find
                        </button>

                    </div>

                    {{-- LOOKUP MESSAGE --}}
                    <p
                        id="postcodeLookupMessage"
                        class="hidden text-sm mt-2"
                    ></p>

                </div>


                {{-- ======================================================
                    POSTCODE INFORMATION
                ======================================================= --}}

                <div
                    id="postcodeInformation"
                    class="hidden mt-4 max-w-2xl bg-slate-50 border border-slate-200 rounded-xl p-4"
                >

                    <div class="flex items-start gap-3">

                        <div
                            class="w-9 h-9 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center flex-shrink-0"
                        >
                            <svg
                                class="w-5 h-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"
                                />

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                />
                            </svg>
                        </div>

                        <div>

                            <p class="text-sm font-semibold text-gray-900">
                                Postcode verified
                            </p>

                            <p
                                id="postcodeLocation"
                                class="text-sm text-gray-600 mt-1"
                            >
                                -
                            </p>

                        </div>

                    </div>

                </div>


                {{-- ======================================================
                    HOME ADDRESS
                ======================================================= --}}

                <div class="mt-4">

                    <label
                        for="homeAddress"
                        class="text-sm font-medium text-gray-700"
                    >
                        Full Home Address
                    </label>

                    <textarea
                        id="homeAddress"
                        name="address"
                        rows="4"
                        placeholder="Enter your house number, street, town, etc."
                        class="w-full border rounded-lg p-3 mt-1"
                    >{{ old('address') }}</textarea>

                    <p class="text-xs text-gray-500 mt-1">
                        Please include your house number and street name.
                    </p>

                </div>

            </div>

            {{-- ==========================================================
                EMERGENCY CONTACT
            =========================================================== --}}

            <div class="border-t mt-8 pt-6">

                <h3 class="text-lg font-semibold text-gray-900">
                    Emergency Contact
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    Someone we can contact in case of an emergency.
                </p>

            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-5">

                {{-- EMERGENCY CONTACT NAME --}}
                <div>

                    <label class="text-sm font-medium text-gray-700">
                        Emergency Contact Name
                    </label>

                    <input
                        type="text"
                        name="emergency_contact_name"
                        value="{{ old('emergency_contact_name') }}"
                        class="w-full border rounded-lg p-3 mt-1"
                    >

                </div>

                {{-- EMERGENCY CONTACT PHONE --}}
                <div>

                    <label class="text-sm font-medium text-gray-700">
                        Emergency Contact Number
                    </label>

                    <input
                        type="text"
                        name="emergency_contact_phone"
                        value="{{ old('emergency_contact_phone') }}"
                        class="w-full border rounded-lg p-3 mt-1"
                    >

                </div>

            </div>


            {{-- ==========================================================
                RIGHT TO WORK
            =========================================================== --}}

            <div class="border-t mt-8 pt-6">

                <h3 class="text-lg font-semibold text-gray-900">
                    Right To Work
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    Provide your right to work information.
                </p>

            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-5">

                {{-- RIGHT TO WORK STATUS --}}
                <div>

                    <label class="text-sm font-medium text-gray-700">
                        Right To Work Status
                    </label>

                    <select
                        name="work_status"
                        class="w-full border rounded-lg p-3 mt-1"
                    >

                        <option value="">
                            Select
                        </option>

                        <option
                            value="UK Citizen"
                            {{ old('work_status') === 'UK Citizen' ? 'selected' : '' }}
                        >
                            UK Citizen
                        </option>

                        <option
                            value="BRP"
                            {{ old('work_status') === 'BRP' ? 'selected' : '' }}
                        >
                            BRP
                        </option>

                        <option
                            value="Visa"
                            {{ old('work_status') === 'Visa' ? 'selected' : '' }}
                        >
                            Visa
                        </option>

                    </select>

                </div>

                {{-- SHARE CODE --}}
                <div>

                    <label class="text-sm font-medium text-gray-700">
                        Share Code
                    </label>

                    <input
                        type="text"
                        name="share_code"
                        value="{{ old('share_code') }}"
                        class="w-full border rounded-lg p-3 mt-1"
                    >

                </div>

                {{-- EXPIRY DATE --}}
                <div>

                    <label class="text-sm font-medium text-gray-700">
                        Expiry Date
                    </label>

                    <input
                        type="date"
                        name="expiry_date"
                        value="{{ old('expiry_date') }}"
                        class="w-full border rounded-lg p-3 mt-1"
                    >

                </div>

            </div>


            {{-- ==========================================================
                BUTTON
            =========================================================== --}}

            <div class="mt-8">

                <button
                    type="submit"
                    class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg"
                >
                    Save & Continue
                </button>

            </div>

        </form>



        @endif


        {{-- STEP 3 --}}
        @if(auth()->user()->registration_step == 3)
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4 rounded">

            <p class="font-semibold">Important Notice</p>

            <p class="text-sm mt-1">
                Please review all information carefully before submitting.
                Once submitted, this step cannot be edited by you again.
                Any corrections must be requested through our support channels.
            </p>

        </div>
        <h3 class="font-semibold mb-4">
            Employment & Service Details
        </h3>

        <form method="POST" action="{{ route('application.step3') }}">
            @csrf

            {{-- EMPLOYMENT TYPE --}}
            <label class="text-sm font-medium">Employment Type</label>
            <select name="employment_type" class="w-full border p-2 mb-3">
                <option value="">Select</option>
                <option value="Full-Time">Full-Time</option>
                <option value="Part-Time">Part-Time</option>
                <option value="Casual">Casual</option>
            </select>

            {{-- START DATE --}}
            <label class="text-sm font-medium">Start Date</label>
            <input type="date" name="start_date" class="w-full border p-2 mb-3">

            {{-- AVAILABILITY --}}
            <label class="text-sm font-medium">Availability</label>
            <input type="text" name="availability"
                placeholder="e.g. Mon–Fri, 8am–5pm"
                class="w-full border p-2 mb-3">

            {{-- PRIMARY ROLE --}}
            <label class="text-sm font-medium">Primary Service Role</label>
            <select name="primary_role" class="w-full border p-2 mb-3">
                <option value="">Select Role</option>
                <option value="SIA Security">SIA Security</option>
                <option value="Cleaning Services">Cleaning Services</option>
                <option value="Companion Support">Companion Support</option>
                <option value="Back Support Services">Back Support Services</option>
            </select>

            {{-- SKILLS --}}
            <label class="text-sm font-medium">Secondary Skills</label>

            <div class="grid grid-cols-2 gap-2 mb-4">

                <!-- SECONDARY SKILLS -->
<div class="mt-6">

    <label class="block text-sm font-semibold text-gray-700 mb-3">
        Secondary Skills
    </label>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">

        @php
            $skills = [
                'SIA Licensed',
                'Door Supervisor',
                'CCTV Operator',
                'Cleaning Commercial',
                'Cleaning Residential',
                'Care Support Work',
                'Admin Support',
                'Logistics Support',
            ];
        @endphp

        @foreach($skills as $skill)

            <label class="flex items-center gap-2 border rounded-lg p-3 hover:bg-gray-50 cursor-pointer">

                <input type="checkbox"
                       name="secondary_skills[]"
                       value="{{ $skill }}"
                       class="rounded border-gray-300 text-red-600 focus:ring-red-500">

                <span class="text-sm text-gray-700">
                    {{ $skill }}
                </span>

            </label>

        @endforeach

    </div>

</div>

            </div>

            <button class="bg-red-600 text-white px-4 py-2 rounded">
                Save & Continue
            </button>

        </form>

        @endif

        {{-- STEP 4 --}}
            @if(auth()->user()->registration_step == 4)
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4 rounded">

            <p class="font-semibold">Important Notice</p>

            <p class="text-sm mt-1">
                Please review all information carefully before submitting.
                Once submitted, this step cannot be edited by you again.
                Any corrections must be requested through our support channels.
            </p>

        </div>
            <h3 class="font-semibold mb-4">
                Role-Specific Requirements
            </h3>

            <form method="POST" action="{{ route('application.step4') }}" enctype="multipart/form-data">
                @csrf

                @php
                    $role = auth()->user()->employee->primary_role;
                @endphp

                {{-- ========================= --}}
                {{-- SIA SECURITY --}}
                {{-- ========================= --}}
                @if($role == 'SIA Security')

                    <div class="mb-3">
                        <label class="text-sm font-medium text-gray-700">
                            SIA Licence Number
                        </label>
                        <input type="text"
                            name="role_data[sia_licence_number]"
                            class="w-full border p-2 rounded mt-1"
                            placeholder="Enter your SIA licence number">
                    </div>

                    <div class="mb-3">
                        <label class="text-sm font-medium text-gray-700">
                            Licence Type
                        </label>
                        <input type="text"
                            name="role_data[licence_type]"
                            class="w-full border p-2 rounded mt-1"
                            placeholder="e.g. Door Supervisor">
                    </div>

                    <div class="mb-3">
                        <label class="text-sm font-medium text-gray-700">
                            Licence Expiry Date
                        </label>
                        <input type="date"
                            name="role_data[expiry_date]"
                            class="w-full border p-2 rounded mt-1">
                    </div>

                    <div class="mb-3">
                        <label class="text-sm font-medium text-gray-700">
                            First Aid Certified?
                        </label>
                        <select name="role_data[first_aid]"
                                class="w-full border p-2 rounded mt-1">
                            <option value="">Select Option</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="text-sm font-medium text-gray-700">
                            First Aid Expiry Date
                        </label>
                        <input type="date"
                            name="role_data[first_aid_expiry]"
                            class="w-full border p-2 rounded mt-1">
                    </div>

                    <div class="mb-3">
                        <label class="text-sm font-medium text-gray-700">
                            SIA Badge Upload
                        </label>
                        <input type="file"
                            name="badge"
                            class="w-full border p-2 rounded mt-1">
                        <small class="text-gray-500">Upload your SIA badge (PDF, JPG, PNG)</small>
                    </div>

                @endif

                {{-- ========================= --}}
                {{-- CLEANING SERVICES --}}
                {{-- ========================= --}}
                @if($role == 'Cleaning Services')

                    <div class="mb-3">
                        <label class="text-sm font-medium text-gray-700">
                            Years of Cleaning Experience
                        </label>
                        <input type="number"
                            name="role_data[experience_years]"
                            class="w-full border p-2 rounded mt-1"
                            placeholder="e.g. 3">
                    </div>

                    <div class="mb-3">
                        <label class="text-sm font-medium text-gray-700">
                            Equipment Knowledge
                        </label>
                        <select name="role_data[equipment_knowledge]"
                                class="w-full border p-2 rounded mt-1">
                            <option value="">Select Option</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="text-sm font-medium text-gray-700">
                            COSHH Awareness
                        </label>
                        <select name="role_data[coshh]"
                                class="w-full border p-2 rounded mt-1">
                            <option value="">Select Option</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>

                @endif

                {{-- ========================= --}}
                {{-- COMPANION SUPPORT --}}
                {{-- ========================= --}}
                @if($role == 'Companion Support')

                    <div class="mb-3">
                        <label class="text-sm font-medium text-gray-700">
                            DBS Status
                        </label>
                        <select name="role_data[dbs_status]"
                                class="w-full border p-2 rounded mt-1">
                            <option value="">Select Status</option>
                            <option value="Clear">Clear</option>
                            <option value="Pending">Pending</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="text-sm font-medium text-gray-700">
                            DBS Certificate Number
                        </label>
                        <input type="text"
                            name="role_data[dbs_number]"
                            class="w-full border p-2 rounded mt-1"
                            placeholder="Enter DBS number">
                    </div>

                    <div class="mb-3">
                        <label class="text-sm font-medium text-gray-700">
                            Care Experience
                        </label>
                        <input type="text"
                            name="role_data[care_experience]"
                            class="w-full border p-2 rounded mt-1"
                            placeholder="e.g. 2 years elderly care">
                    </div>

                    <div class="mb-3">
                        <label class="text-sm font-medium text-gray-700">
                            Safeguarding Training Completed?
                        </label>
                        <select name="role_data[safeguarding]"
                                class="w-full border p-2 rounded mt-1">
                            <option value="">Select Option</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>

                @endif

                {{-- ========================= --}}
                {{-- BACK SUPPORT --}}
                {{-- ========================= --}}
                @if($role == 'Back Support Services')

                    <div class="mb-3">
                        <label class="text-sm font-medium text-gray-700">
                            Department Area
                        </label>
                        <select name="role_data[area]"
                                class="w-full border p-2 rounded mt-1">
                            <option value="">Select Area</option>
                            <option value="Admin">Admin</option>
                            <option value="Logistics">Logistics</option>
                            <option value="Operations">Operations</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="text-sm font-medium text-gray-700">
                            Software Skills
                        </label>
                        <input type="text"
                            name="role_data[software_skills]"
                            class="w-full border p-2 rounded mt-1"
                            placeholder="e.g. Excel, CRM, SAP">
                    </div>

                    <div class="mb-3">
                        <label class="text-sm font-medium text-gray-700">
                            Previous Experience
                        </label>
                        <textarea name="role_data[experience]"
                                class="w-full border p-2 rounded mt-1"
                                rows="3"
                                placeholder="Describe your experience"></textarea>
                    </div>

                @endif

                <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded mt-4">
                    Save & Continue
                </button>

            </form>

            @endif

            {{-- STEP 5 --}}
            @if(auth()->user()->registration_step == 5)
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4 rounded">

            <p class="font-semibold">Important Notice</p>

            <p class="text-sm mt-1">
                Please review all information carefully before submitting.
                Once submitted, this step cannot be edited by you again.
                Any corrections must be requested through our support channels.
            </p>

        </div>

            <h3 class="font-semibold mb-4">
                Payroll Information
            </h3>

            <form method="POST" action="{{ route('application.step5') }}">
                @csrf

                {{-- BANK NAME --}}
                <div class="mb-3">
                    <label class="text-sm font-medium text-gray-700">
                        Bank Name
                    </label>
                    <input type="text"
                        name="bank_name"
                        class="w-full border p-2 rounded mt-1"
                        placeholder="e.g. Barclays, HSBC">
                </div>

                {{-- ACCOUNT NUMBER --}}
                <div class="mb-3">
                    <label class="text-sm font-medium text-gray-700">
                        Account Number
                    </label>
                    <input type="text"
                        name="account_number"
                        class="w-full border p-2 rounded mt-1"
                        placeholder="Enter account number">
                </div>

                {{-- SORT CODE --}}
                <div class="mb-3">
                    <label class="text-sm font-medium text-gray-700">
                        Sort Code
                    </label>
                    <input type="text"
                        name="sort_code"
                        class="w-full border p-2 rounded mt-1"
                        placeholder="e.g. 20-30-40">
                </div>

                {{-- UTR --}}
                <div class="mb-3">
                    <label class="text-sm font-medium text-gray-700">
                        UTR (Optional)
                    </label>
                    <input type="text"
                        name="utr"
                        class="w-full border p-2 rounded mt-1"
                        placeholder="Self-employed UTR number">
                </div>

                {{-- PAYMENT TYPE --}}
                <div class="mb-3">
                    <label class="text-sm font-medium text-gray-700">
                        Payment Type
                    </label>

                    <select name="payment_type"
                            class="w-full border p-2 rounded mt-1">

                        <option value="">Select Payment Type</option>
                        <option value="PAYE">PAYE</option>
                        <option value="Self-Employed">Self-Employed</option>

                    </select>
                </div>

                <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
                    Save & Continue
                </button>

            </form>

            @endif

            {{-- STEP 6 --}}
            @if(auth()->user()->registration_step == 6)
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4 rounded">

            <p class="font-semibold">Important Notice</p>

            <p class="text-sm mt-1">
                Please review all information carefully before submitting.
                Once submitted, this step cannot be edited by you again.
                Any corrections must be requested through our support channels.
            </p>

        </div>

            <h3 class="font-semibold mb-4">
                Upload Required Documents
            </h3>

            <form method="POST" action="{{ route('application.step6') }}" enctype="multipart/form-data">
                @csrf

                {{-- PASSPORT --}}
                <div class="mb-3">
                    <label class="text-sm font-medium">Passport</label>
                    <input type="file" name="passport" class="w-full border p-2 rounded mt-1">
                </div>

                {{-- NI PROOF --}}
                <div class="mb-3">
                    <label class="text-sm font-medium">NI Proof</label>
                    <input type="file" name="ni_proof" class="w-full border p-2 rounded mt-1">
                </div>

                {{-- DBS --}}
                <div class="mb-3">
                    <label class="text-sm font-medium">DBS Certificate</label>
                    <input type="file" name="dbs" class="w-full border p-2 rounded mt-1">
                </div>

                {{-- SIA --}}
                <div class="mb-3">
                    <label class="text-sm font-medium">SIA Licence</label>
                    <input type="file" name="sia_license" class="w-full border p-2 rounded mt-1">
                </div>

                {{-- CERTIFICATES --}}
                <div class="mb-3">
                    <label class="text-sm font-medium">Other Certificates</label>
                    <input type="file" name="certificates" class="w-full border p-2 rounded mt-1">
                </div>

                <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
                    Submit Documents
                </button>

            </form>

            @endif

    </div>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Elements
    |--------------------------------------------------------------------------
    */

    const postcodeInput =
        document.getElementById('postcode');

    const findPostcodeBtn =
        document.getElementById('findPostcodeBtn');

    const postcodeInformation =
        document.getElementById('postcodeInformation');

    const postcodeLocation =
        document.getElementById('postcodeLocation');

    const postcodeLookupMessage =
        document.getElementById('postcodeLookupMessage');


    /*
    |--------------------------------------------------------------------------
    | Find Postcode
    |--------------------------------------------------------------------------
    */

    findPostcodeBtn.addEventListener(
        'click',
        lookupPostcode
    );


    /*
    |--------------------------------------------------------------------------
    | Enter Key
    |--------------------------------------------------------------------------
    */

    postcodeInput.addEventListener(
        'keydown',
        function (event) {

            if (event.key === 'Enter') {

                event.preventDefault();

                lookupPostcode();

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Lookup Postcode
    |--------------------------------------------------------------------------
    */

    async function lookupPostcode()
    {
        let postcode =
            postcodeInput.value.trim();


        /*
        |--------------------------------------------------------------------------
        | Validate Input
        |--------------------------------------------------------------------------
        */

        if (!postcode) {

            showMessage(
                'Please enter your postcode.',
                'error'
            );

            postcodeInput.focus();

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Normalise Postcode
        |--------------------------------------------------------------------------
        */

        postcode =
            postcode
                .toUpperCase()
                .replace(/\s+/g, ' ');


        postcodeInput.value =
            postcode;


        /*
        |--------------------------------------------------------------------------
        | Loading State
        |--------------------------------------------------------------------------
        */

        findPostcodeBtn.disabled = true;

        findPostcodeBtn.innerHTML = `
            <span class="inline-flex items-center gap-2">

                <svg
                    class="animate-spin h-4 w-4"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                >

                    <circle
                        class="opacity-25"
                        cx="12"
                        cy="12"
                        r="10"
                        stroke="currentColor"
                        stroke-width="4"
                    ></circle>

                    <path
                        class="opacity-75"
                        fill="currentColor"
                        d="M4 12a8 8 0 018-8v4l3-3-3-3v4A10 10 0 002 12h2z"
                    ></path>

                </svg>

                Checking...

            </span>
        `;


        hideMessage();

        postcodeInformation.classList.add(
            'hidden'
        );


        /*
        |--------------------------------------------------------------------------
        | API Request
        |--------------------------------------------------------------------------
        */

        try {

            const response = await fetch(
                `https://api.postcodes.io/postcodes/${encodeURIComponent(postcode)}`,
                {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json'
                    }
                }
            );


            const data =
                await response.json();


            /*
            |--------------------------------------------------------------------------
            | Invalid Postcode
            |--------------------------------------------------------------------------
            */

            if (!response.ok || data.status !== 200) {

                showMessage(
                    'We could not find this postcode. Please check and try again.',
                    'error'
                );

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | Postcode Result
            |--------------------------------------------------------------------------
            */

            const result =
                data.result;


            /*
            |--------------------------------------------------------------------------
            | Location Information
            |--------------------------------------------------------------------------
            */

            const locationParts = [

                result.admin_ward,

                result.admin_district,

                result.parliamentary_constituency,

                result.region

            ].filter(function (value) {

                return value &&
                    value.trim() !== '';

            });


            postcodeLocation.textContent =
                locationParts.join(', ');


            /*
            |--------------------------------------------------------------------------
            | Show Successful Lookup
            |--------------------------------------------------------------------------
            */

            postcodeInformation.classList.remove(
                'hidden'
            );


            showMessage(
                'Postcode verified successfully.',
                'success'
            );

        } catch (error) {

            console.error(
                'Postcode lookup error:',
                error
            );

            showMessage(
                'Unable to verify the postcode. Please try again.',
                'error'
            );

        } finally {

            /*
            |--------------------------------------------------------------------------
            | Reset Button
            |--------------------------------------------------------------------------
            */

            findPostcodeBtn.disabled = false;

            findPostcodeBtn.textContent =
                'Find';

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Show Message
    |--------------------------------------------------------------------------
    */

    function showMessage(
        message,
        type
    )
    {
        postcodeLookupMessage.textContent =
            message;

        postcodeLookupMessage.classList.remove(
            'hidden',
            'text-red-600',
            'text-green-600'
        );

        if (type === 'error') {

            postcodeLookupMessage.classList.add(
                'text-red-600'
            );

        } else {

            postcodeLookupMessage.classList.add(
                'text-green-600'
            );

        }
    }


    /*
    |--------------------------------------------------------------------------
    | Hide Message
    |--------------------------------------------------------------------------
    */

    function hideMessage()
    {
        postcodeLookupMessage.classList.add(
            'hidden'
        );
    }

});

</script>
@endsection