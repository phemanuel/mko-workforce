@extends('layouts.staff')

@section('page-title', 'My Profile')

@section('content')

@php
    $profile = $employee->profile;
    $roleDetail = $employee->roleDetail;
    $eligibility = $employee->workEligibility;
    $payroll = $employee->payroll;

    $fullName = trim(implode(' ', array_filter([
        $user->first_name,
        $user->middle_name,
        $user->last_name,
    ])));

    if ($fullName === '') {
        $fullName = $user->name ?? 'Staff Member';
    }

    $initials = collect(explode(' ', $fullName))
        ->filter()
        ->take(2)
        ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
        ->implode('');

    $secondarySkills = $roleDetail?->secondary_skills ?? [];

    if (!is_array($secondarySkills)) {
        $secondarySkills = [];
    }

    $accountNumber = $payroll?->account_number;
    $sortCode = $payroll?->sort_code;
    $utr = $payroll?->utr;

    $maskValue = function ($value, $visible = 4) {
        if (blank($value)) {
            return 'Not provided';
        }

        $value = (string) $value;
        $length = strlen($value);

        if ($length <= $visible) {
            return str_repeat('•', $length);
        }

        return str_repeat('•', $length - $visible)
            . substr($value, -$visible);
    };

    $documentCount = $employee->documents->count();

    $approvedDocuments = $employee->documents
        ->filter(fn ($document) =>
            strtolower((string) ($document->status ?? '')) === 'approved'
        )
        ->count();
@endphp

<div class="space-y-6">

    {{-- ============================================================
         PAGE HEADER
    ============================================================ --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                <a href="{{ route('staff.dashboard') }}"
                   class="hover:text-gray-900 transition">
                    Dashboard
                </a>

                <i class="fas fa-chevron-right text-xs"></i>

                <span class="text-gray-900">My Profile</span>
            </div>

            <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-gray-900">
                My Profile
            </h1>

            <p class="text-sm md:text-base text-gray-500 mt-2">
                Manage your personal information and view your employment details.
            </p>
        </div>

        <div class="flex items-center gap-3">

            <span class="inline-flex items-center gap-2 rounded-full
                         bg-green-50 px-3 py-2 text-xs font-semibold text-green-700">

                <span class="h-2 w-2 rounded-full bg-green-500"></span>

                {{ ucfirst($employee->status ?? 'Active') }}

            </span>

        </div>

    </div>


    {{-- ============================================================
         FLASH MESSAGE
    ============================================================ --}}
    @if(session('success'))
        <div class="flex items-start gap-3 rounded-xl border border-green-200
                    bg-green-50 p-4 text-green-800"
             role="status">

            <i class="fas fa-check-circle mt-1"></i>

            <div>
                <p class="font-semibold text-sm">Profile updated</p>

                <p class="text-sm mt-1">
                    {{ session('success') }}
                </p>
            </div>

        </div>
    @endif


    @if(session('error'))
        <div class="flex items-start gap-3 rounded-xl border border-red-200
                    bg-red-50 p-4 text-red-800"
             role="alert">

            <i class="fas fa-exclamation-circle mt-1"></i>

            <p class="text-sm">{{ session('error') }}</p>

        </div>
    @endif


    {{-- ============================================================
         VALIDATION ERRORS
    ============================================================ --}}
    @if($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 p-4"
             role="alert">

            <div class="flex items-center gap-2 text-red-800 font-semibold">
                <i class="fas fa-exclamation-circle"></i>
                Please correct the following errors.
            </div>

            <ul class="mt-3 ml-5 list-disc space-y-1 text-sm text-red-700">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>
    @endif


    {{-- ============================================================
         PROFILE HERO
    ============================================================ --}}
    <div class="overflow-hidden rounded-2xl border border-gray-100
                bg-white shadow-sm">

        <div class="h-2 bg-gradient-to-r from-gray-900 via-gray-700 to-gray-400"></div>

        <div class="p-5 md:p-7">

            <div class="flex flex-col gap-5 sm:flex-row sm:items-center
                        sm:justify-between">

                <div class="flex items-center gap-4 min-w-0">

                    <div class="flex h-16 w-16 shrink-0 items-center
                                justify-center rounded-2xl bg-gray-900
                                text-xl font-bold text-white shadow-sm">

                        {{ $initials ?: 'SM' }}

                    </div>

                    <div class="min-w-0">

                        <h2 class="text-xl md:text-2xl font-bold text-gray-900 break-words">
                            {{ $fullName }}
                        </h2>

                        <p class="mt-1 text-sm text-gray-500">
                            {{ $employee->primary_role ?: 'Role not assigned' }}
                        </p>

                        <div class="mt-3 flex flex-wrap items-center gap-2">

                            @if($employee->employee_code)
                                <span class="inline-flex items-center rounded-lg
                                             border border-gray-200 bg-gray-50
                                             px-2.5 py-1 text-xs font-medium text-gray-600">

                                    <i class="fas fa-id-badge mr-2"></i>

                                    {{ $employee->employee_code }}

                                </span>
                            @endif

                            <span class="inline-flex items-center rounded-lg
                                         bg-blue-50 px-2.5 py-1 text-xs
                                         font-medium text-blue-700">

                                <i class="fas fa-user-check mr-2"></i>

                                Staff Account

                            </span>

                        </div>

                    </div>

                </div>

                <div class="flex flex-wrap gap-3">

                    <button
                        type="button"
                        onclick="document.getElementById('personalInformation').scrollIntoView({behavior: 'smooth', block: 'start'});"
                        class="inline-flex items-center justify-center gap-2
                               rounded-xl bg-gray-900 px-4 py-3 text-sm
                               font-semibold text-white transition
                               hover:bg-gray-700">

                        <i class="fas fa-pen"></i>

                        Edit Personal Details

                    </button>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
         PROFILE SUMMARY CARDS
    ============================================================ --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

        {{-- Employment --}}
        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div class="flex h-11 w-11 items-center justify-center
                            rounded-xl bg-blue-50 text-blue-600">

                    <i class="fas fa-briefcase text-lg"></i>

                </div>

                <span class="text-xs font-medium text-gray-400">
                    EMPLOYMENT
                </span>

            </div>

            <p class="mt-4 text-lg font-bold text-gray-900">
                {{ $employee->employment_type ?: 'Not specified' }}
            </p>

            <p class="mt-1 text-sm text-gray-500">
                Employment type
            </p>

        </div>


        {{-- Start Date --}}
        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div class="flex h-11 w-11 items-center justify-center
                            rounded-xl bg-purple-50 text-purple-600">

                    <i class="fas fa-calendar-alt text-lg"></i>

                </div>

                <span class="text-xs font-medium text-gray-400">
                    START DATE
                </span>

            </div>

            <p class="mt-4 text-lg font-bold text-gray-900">

                {{ $employee->start_date
                    ? \Carbon\Carbon::parse($employee->start_date)->format('d M Y')
                    : 'Not specified' }}

            </p>

            <p class="mt-1 text-sm text-gray-500">
                Employment commencement
            </p>

        </div>


        {{-- Documents --}}
        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div class="flex h-11 w-11 items-center justify-center
                            rounded-xl bg-green-50 text-green-600">

                    <i class="fas fa-file-alt text-lg"></i>

                </div>

                <span class="text-xs font-medium text-gray-400">
                    DOCUMENTS
                </span>

            </div>

            <p class="mt-4 text-lg font-bold text-gray-900">
                {{ $documentCount }}
            </p>

            <p class="mt-1 text-sm text-gray-500">
                {{ $approvedDocuments }} approved
            </p>

        </div>


        {{-- Profile Contact --}}
        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div class="flex h-11 w-11 items-center justify-center
                            rounded-xl bg-amber-50 text-amber-600">

                    <i class="fas fa-address-card text-lg"></i>

                </div>

                <span class="text-xs font-medium text-gray-400">
                    CONTACT
                </span>

            </div>

            <p class="mt-4 truncate text-lg font-bold text-gray-900"
               title="{{ $profile?->phone ?: 'Not provided' }}">

                {{ $profile?->phone ?: 'Not provided' }}

            </p>

            <p class="mt-1 text-sm text-gray-500">
                Contact phone number
            </p>

        </div>

    </div>


    {{-- ============================================================
         MAIN PROFILE CONTENT
    ============================================================ --}}
    <div class="grid grid-cols-1 items-start gap-6 xl:grid-cols-3">

        {{-- ========================================================
             LEFT COLUMN
        ======================================================== --}}
        <div class="space-y-6 xl:col-span-2">

            {{-- ====================================================
                 PERSONAL INFORMATION
            ==================================================== --}}
            <section id="personalInformation"
                     class="scroll-mt-6 overflow-hidden rounded-2xl
                            border border-gray-100 bg-white shadow-sm">

                <div class="flex flex-col gap-3 border-b border-gray-100
                            p-5 sm:flex-row sm:items-center sm:justify-between
                            md:p-6">

                    <div class="flex items-center gap-3">

                        <div class="flex h-11 w-11 items-center justify-center
                                    rounded-xl bg-gray-100 text-gray-700">

                            <i class="fas fa-user text-lg"></i>

                        </div>

                        <div>
                            <h3 class="font-bold text-gray-900">
                                Personal Information
                            </h3>

                            <p class="mt-1 text-sm text-gray-500">
                                Your personal and contact details
                            </p>
                        </div>

                    </div>

                    <span class="inline-flex items-center gap-2 text-xs
                                 font-medium text-gray-500">

                        <i class="fas fa-lock"></i>

                        Account name managed by administrator

                    </span>

                </div>


                <form
                    method="POST"
                    action="{{ route('staff.profile.update') }}"
                    class="p-5 md:p-6">

                    @csrf
                    @method('PUT')

                    {{-- Account Information --}}
                    <div class="mb-6">

                        <h4 class="mb-4 text-xs font-bold uppercase
                                   tracking-wider text-gray-400">

                            Account Information

                        </h4>

                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                            <div>

                                <label class="mb-2 block text-sm
                                              font-semibold text-gray-700">

                                    First Name

                                </label>

                                <input
                                    type="text"
                                    value="{{ $user->first_name ?: 'Not provided' }}"
                                    readonly
                                    class="w-full rounded-xl border border-gray-200
                                           bg-gray-50 px-4 py-3 text-sm
                                           text-gray-500 outline-none">

                                <p class="mt-1.5 text-xs text-gray-400">
                                    Contact your administrator to change your name.
                                </p>

                            </div>


                            <div>

                                <label class="mb-2 block text-sm
                                              font-semibold text-gray-700">

                                    Last Name

                                </label>

                                <input
                                    type="text"
                                    value="{{ $user->last_name ?: 'Not provided' }}"
                                    readonly
                                    class="w-full rounded-xl border border-gray-200
                                           bg-gray-50 px-4 py-3 text-sm
                                           text-gray-500 outline-none">

                            </div>


                            <div>

                                <label class="mb-2 block text-sm
                                              font-semibold text-gray-700">

                                    Email Address

                                </label>

                                <input
                                    type="email"
                                    value="{{ $user->email }}"
                                    readonly
                                    class="w-full rounded-xl border border-gray-200
                                           bg-gray-50 px-4 py-3 text-sm
                                           text-gray-500 outline-none">

                                <p class="mt-1.5 text-xs text-gray-400">
                                    Account email is managed separately.
                                </p>

                            </div>


                            <div>

                                <label for="profile_phone"
                                       class="mb-2 block text-sm
                                              font-semibold text-gray-700">

                                    Phone Number

                                </label>

                                <input
                                    type="tel"
                                    id="profile_phone"
                                    name="phone"
                                    value="{{ old('phone', $profile?->phone) }}"
                                    maxlength="30"
                                    autocomplete="tel"
                                    placeholder="Enter your phone number"
                                    class="w-full rounded-xl border
                                           @error('phone')
                                               border-red-400
                                           @else
                                               border-gray-200
                                           @enderror
                                           bg-white px-4 py-3 text-sm
                                           text-gray-900 outline-none
                                           transition focus:border-gray-900
                                           focus:ring-2 focus:ring-gray-900/10">

                                @error('phone')
                                    <p class="mt-1.5 text-xs text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>

                        </div>

                    </div>


                    {{-- Personal Details --}}
                    <div class="border-t border-gray-100 pt-6">

                        <h4 class="mb-4 text-xs font-bold uppercase
                                   tracking-wider text-gray-400">

                            Personal Details

                        </h4>

                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                            <div>

                                <label for="profile_date_of_birth"
                                       class="mb-2 block text-sm
                                              font-semibold text-gray-700">

                                    Date of Birth

                                </label>

                                <input
                                    type="date"
                                    id="profile_date_of_birth"
                                    name="date_of_birth"
                                    value="{{ old(
                                        'date_of_birth',
                                        $profile?->date_of_birth
                                            ? \Carbon\Carbon::parse($profile->date_of_birth)->format('Y-m-d')
                                            : ''
                                    ) }}"
                                    max="{{ now()->subDay()->format('Y-m-d') }}"
                                    class="w-full rounded-xl border
                                           @error('date_of_birth')
                                               border-red-400
                                           @else
                                               border-gray-200
                                           @enderror
                                           bg-white px-4 py-3 text-sm
                                           text-gray-900 outline-none
                                           focus:border-gray-900
                                           focus:ring-2 focus:ring-gray-900/10">

                                @error('date_of_birth')
                                    <p class="mt-1.5 text-xs text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>


                            <div>

                                <label for="profile_gender"
                                       class="mb-2 block text-sm
                                              font-semibold text-gray-700">

                                    Gender

                                </label>

                                <select
                                    id="profile_gender"
                                    name="gender"
                                    class="w-full rounded-xl border
                                           @error('gender')
                                               border-red-400
                                           @else
                                               border-gray-200
                                           @enderror
                                           bg-white px-4 py-3 text-sm
                                           text-gray-900 outline-none
                                           focus:border-gray-900
                                           focus:ring-2 focus:ring-gray-900/10">

                                    <option value="">Select gender</option>

                                    @foreach([
                                        'Male',
                                        'Female',
                                        'Other',
                                        'Prefer not to say'
                                    ] as $gender)

                                        <option
                                            value="{{ $gender }}"
                                            @selected(old('gender', $profile?->gender) === $gender)>

                                            {{ $gender }}

                                        </option>

                                    @endforeach

                                </select>

                                @error('gender')
                                    <p class="mt-1.5 text-xs text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>


                            <div>

                                <label for="profile_nationality"
                                       class="mb-2 block text-sm
                                              font-semibold text-gray-700">

                                    Nationality

                                </label>

                                <input
                                    type="text"
                                    id="profile_nationality"
                                    name="nationality"
                                    value="{{ old('nationality', $profile?->nationality) }}"
                                    maxlength="100"
                                    placeholder="Enter your nationality"
                                    class="w-full rounded-xl border
                                           @error('nationality')
                                               border-red-400
                                           @else
                                               border-gray-200
                                           @enderror
                                           bg-white px-4 py-3 text-sm
                                           text-gray-900 outline-none
                                           focus:border-gray-900
                                           focus:ring-2 focus:ring-gray-900/10">

                                @error('nationality')
                                    <p class="mt-1.5 text-xs text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>


                            <div>

                                <label for="profile_postcode"
                                       class="mb-2 block text-sm
                                              font-semibold text-gray-700">

                                    Postcode

                                </label>

                                <input
                                    type="text"
                                    id="profile_postcode"
                                    name="postcode"
                                    value="{{ old('postcode', $profile?->postcode) }}"
                                    maxlength="20"
                                    autocomplete="postal-code"
                                    placeholder="Enter your postcode"
                                    class="w-full rounded-xl border
                                           @error('postcode')
                                               border-red-400
                                           @else
                                               border-gray-200
                                           @enderror
                                           bg-white px-4 py-3 text-sm
                                           text-gray-900 outline-none
                                           focus:border-gray-900
                                           focus:ring-2 focus:ring-gray-900/10">

                                @error('postcode')
                                    <p class="mt-1.5 text-xs text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>


                            <div class="sm:col-span-2">

                                <label for="profile_address"
                                       class="mb-2 block text-sm
                                              font-semibold text-gray-700">

                                    Residential Address

                                </label>

                                <textarea
                                    id="profile_address"
                                    name="address"
                                    rows="3"
                                    maxlength="500"
                                    autocomplete="street-address"
                                    placeholder="Enter your residential address"
                                    class="w-full resize-y rounded-xl border
                                           @error('address')
                                               border-red-400
                                           @else
                                               border-gray-200
                                           @enderror
                                           bg-white px-4 py-3 text-sm
                                           text-gray-900 outline-none
                                           focus:border-gray-900
                                           focus:ring-2 focus:ring-gray-900/10">{{ old('address', $profile?->address) }}</textarea>

                                @error('address')
                                    <p class="mt-1.5 text-xs text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>

                        </div>

                    </div>


                    {{-- Emergency Contact --}}
                    <div class="mt-6 border-t border-gray-100 pt-6">

                        <h4 class="mb-1 text-xs font-bold uppercase
                                   tracking-wider text-gray-400">

                            Emergency Contact

                        </h4>

                        <p class="mb-4 text-sm text-gray-500">
                            Keep these details up to date in case of an emergency.
                        </p>

                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                            <div>

                                <label for="profile_emergency_name"
                                       class="mb-2 block text-sm
                                              font-semibold text-gray-700">

                                    Contact Name

                                </label>

                                <input
                                    type="text"
                                    id="profile_emergency_name"
                                    name="emergency_contact_name"
                                    value="{{ old(
                                        'emergency_contact_name',
                                        $profile?->emergency_contact_name
                                    ) }}"
                                    maxlength="150"
                                    placeholder="Enter contact's full name"
                                    class="w-full rounded-xl border
                                           @error('emergency_contact_name')
                                               border-red-400
                                           @else
                                               border-gray-200
                                           @enderror
                                           bg-white px-4 py-3 text-sm
                                           text-gray-900 outline-none
                                           focus:border-gray-900
                                           focus:ring-2 focus:ring-gray-900/10">

                                @error('emergency_contact_name')
                                    <p class="mt-1.5 text-xs text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>


                            <div>

                                <label for="profile_emergency_phone"
                                       class="mb-2 block text-sm
                                              font-semibold text-gray-700">

                                    Contact Phone

                                </label>

                                <input
                                    type="tel"
                                    id="profile_emergency_phone"
                                    name="emergency_contact_phone"
                                    value="{{ old(
                                        'emergency_contact_phone',
                                        $profile?->emergency_contact_phone
                                    ) }}"
                                    maxlength="30"
                                    placeholder="Enter contact's phone number"
                                    class="w-full rounded-xl border
                                           @error('emergency_contact_phone')
                                               border-red-400
                                           @else
                                               border-gray-200
                                           @enderror
                                           bg-white px-4 py-3 text-sm
                                           text-gray-900 outline-none
                                           focus:border-gray-900
                                           focus:ring-2 focus:ring-gray-900/10">

                                @error('emergency_contact_phone')
                                    <p class="mt-1.5 text-xs text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>

                        </div>

                    </div>


                    {{-- Form Actions --}}
                    <div class="mt-6 flex flex-col-reverse gap-3 border-t
                                border-gray-100 pt-5 sm:flex-row
                                sm:items-center sm:justify-between">

                        <p class="text-xs text-gray-400">
                            <i class="fas fa-shield-alt mr-1"></i>
                            Your account name and employment records cannot be
                            changed from this page.
                        </p>

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center
                                   gap-2 rounded-xl bg-gray-900 px-5 py-3
                                   text-sm font-semibold text-white
                                   transition hover:bg-gray-700
                                   focus:outline-none focus:ring-2
                                   focus:ring-gray-900 focus:ring-offset-2">

                            <i class="fas fa-save"></i>

                            Save Changes

                        </button>

                    </div>

                </form>

            </section>


            {{-- ====================================================
                 EMPLOYMENT INFORMATION
            ==================================================== --}}
            <section class="overflow-hidden rounded-2xl border
                            border-gray-100 bg-white shadow-sm">

                <div class="flex items-center gap-3 border-b
                            border-gray-100 p-5 md:p-6">

                    <div class="flex h-11 w-11 items-center justify-center
                                rounded-xl bg-blue-50 text-blue-600">

                        <i class="fas fa-briefcase text-lg"></i>

                    </div>

                    <div class="flex-1">

                        <h3 class="font-bold text-gray-900">
                            Employment Information
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Your current employment details
                        </p>

                    </div>

                    <span class="rounded-full bg-gray-100 px-3 py-1
                                 text-xs font-medium text-gray-600">

                        Read only

                    </span>

                </div>


                <div class="grid grid-cols-1 gap-5 p-5 sm:grid-cols-2 md:p-6">

                    <div>
                        <p class="text-sm text-gray-500">Employee Number</p>

                        <p class="mt-2 font-semibold text-gray-900">
                            {{ $employee->employee_code ?: 'Not assigned' }}
                        </p>
                    </div>


                    <div>
                        <p class="text-sm text-gray-500">Primary Role</p>

                        <p class="mt-2 font-semibold text-gray-900">
                            {{ $employee->primary_role ?: 'Not assigned' }}
                        </p>
                    </div>


                    <div>
                        <p class="text-sm text-gray-500">Employment Type</p>

                        <p class="mt-2 font-semibold text-gray-900">
                            {{ $employee->employment_type ?: 'Not specified' }}
                        </p>
                    </div>


                    <div>
                        <p class="text-sm text-gray-500">Start Date</p>

                        <p class="mt-2 font-semibold text-gray-900">
                            {{ $employee->start_date
                                ? \Carbon\Carbon::parse($employee->start_date)->format('d F Y')
                                : 'Not specified' }}
                        </p>
                    </div>


                    <div>
                        <p class="text-sm text-gray-500">Employment Status</p>

                        <p class="mt-2">

                            <span class="inline-flex items-center rounded-full
                                         bg-gray-100 px-3 py-1 text-xs
                                         font-semibold text-gray-700">

                                {{ ucfirst($employee->status ?? 'Unknown') }}

                            </span>

                        </p>
                    </div>


                    <div>
                        <p class="text-sm text-gray-500">Role Type</p>

                        <p class="mt-2 font-semibold text-gray-900">
                            {{ $roleDetail?->role_type ?: 'Not specified' }}
                        </p>
                    </div>

                </div>

                <div class="border-t border-gray-100 bg-gray-50 px-5 py-4 md:px-6">

                    <p class="text-sm text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        Contact your administrator if any employment information
                        needs to be corrected.
                    </p>

                </div>

            </section>


            {{-- ====================================================
                 SKILLS
            ==================================================== --}}
            <section class="overflow-hidden rounded-2xl border
                            border-gray-100 bg-white shadow-sm">

                <div class="flex items-center gap-3 border-b
                            border-gray-100 p-5 md:p-6">

                    <div class="flex h-11 w-11 items-center justify-center
                                rounded-xl bg-purple-50 text-purple-600">

                        <i class="fas fa-layer-group text-lg"></i>

                    </div>

                    <div class="flex-1">

                        <h3 class="font-bold text-gray-900">
                            Skills & Expertise
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Skills recorded on your employee profile
                        </p>

                    </div>

                    <span class="rounded-full bg-gray-100 px-3 py-1
                                 text-xs font-medium text-gray-600">

                        Read only

                    </span>

                </div>


                <div class="space-y-5 p-5 md:p-6">

                    <div>

                        <p class="mb-3 text-sm font-semibold text-gray-700">
                            Primary Role
                        </p>

                        @if($employee->primary_role)

                            <span class="inline-flex items-center gap-2
                                         rounded-lg border border-blue-100
                                         bg-blue-50 px-3 py-2 text-sm
                                         font-medium text-blue-700">

                                <i class="fas fa-star text-xs"></i>

                                {{ $employee->primary_role }}

                            </span>

                        @else

                            <p class="text-sm text-gray-400">
                                No primary role recorded.
                            </p>

                        @endif

                    </div>


                    <div class="border-t border-gray-100 pt-5">

                        <p class="mb-3 text-sm font-semibold text-gray-700">
                            Secondary Skills
                        </p>

                        <div class="flex flex-wrap gap-2">

                            @forelse($secondarySkills as $skill)

                                @php
                                    $skillLabel = is_array($skill)
                                        ? ($skill['name'] ?? $skill['label'] ?? null)
                                        : $skill;
                                @endphp

                                @if(is_string($skillLabel) && trim($skillLabel) !== '')

                                    <span class="inline-flex items-center rounded-lg
                                                 border border-gray-200 bg-gray-50
                                                 px-3 py-2 text-sm text-gray-700">

                                        {{ $skillLabel }}

                                    </span>

                                @endif

                            @empty

                                <p class="text-sm text-gray-400">
                                    No secondary skills recorded.
                                </p>

                            @endforelse

                        </div>

                    </div>


                    @if($employee->skills->isNotEmpty())

                        <div class="border-t border-gray-100 pt-5">

                            <p class="mb-3 text-sm font-semibold text-gray-700">
                                Additional Recorded Skills
                            </p>

                            <div class="flex flex-wrap gap-2">

                                @foreach($employee->skills as $skill)

                                    <span class="inline-flex items-center rounded-lg
                                                 border border-gray-200 bg-white
                                                 px-3 py-2 text-sm text-gray-700">

                                        {{ $skill->name ?? 'Skill' }}

                                    </span>

                                @endforeach

                            </div>

                        </div>

                    @endif

                </div>

            </section>

        </div>


        {{-- ========================================================
             RIGHT COLUMN
        ======================================================== --}}
        <div class="space-y-6">

            {{-- ====================================================
                 WORK ELIGIBILITY
            ==================================================== --}}
            <section class="overflow-hidden rounded-2xl border
                            border-gray-100 bg-white shadow-sm">

                <div class="flex items-center gap-3 border-b
                            border-gray-100 p-5">

                    <div class="flex h-10 w-10 items-center justify-center
                                rounded-xl bg-green-50 text-green-600">

                        <i class="fas fa-shield-alt"></i>

                    </div>

                    <div>
                        <h3 class="font-bold text-gray-900">
                            Work Eligibility
                        </h3>

                        <p class="mt-1 text-xs text-gray-500">
                            Verification information
                        </p>
                    </div>

                </div>


                <div class="space-y-5 p-5">

                    <div>

                        <p class="text-sm text-gray-500">
                            Work Status
                        </p>

                        <p class="mt-2 font-semibold text-gray-900">
                            {{ $eligibility?->work_status ?: 'Not recorded' }}
                        </p>

                    </div>


                    <div>

                        <p class="text-sm text-gray-500">
                            Share Code
                        </p>

                        <p class="mt-2 font-semibold text-gray-900">

                            {{ $eligibility?->share_code ?: 'Not provided' }}

                        </p>

                    </div>


                    <div>

                        <p class="text-sm text-gray-500">
                            Expiry Date
                        </p>

                        <p class="mt-2 font-semibold text-gray-900">

                            {{ $eligibility?->expiry_date
                                ? \Carbon\Carbon::parse($eligibility->expiry_date)->format('d M Y')
                                : 'Not specified' }}

                        </p>

                        @if($eligibility?->expiry_date)

                            @if(\Carbon\Carbon::parse($eligibility->expiry_date)->isPast())

                                <p class="mt-2 text-xs font-medium text-red-600">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    The recorded expiry date has passed.
                                </p>

                            @elseif(\Carbon\Carbon::parse($eligibility->expiry_date)->lte(now()->addDays(30)))

                                <p class="mt-2 text-xs font-medium text-amber-600">
                                    <i class="fas fa-clock mr-1"></i>
                                    Expiry is within the next 30 days.
                                </p>

                            @endif

                        @endif

                    </div>


                    <div class="rounded-xl border border-amber-100
                                bg-amber-50 p-3">

                        <p class="text-xs leading-5 text-amber-800">

                            <i class="fas fa-lock mr-1"></i>

                            These details are managed by your administrator.
                            Contact them if your eligibility information needs
                            updating.

                        </p>

                    </div>

                </div>

            </section>


            {{-- ====================================================
                 PAYROLL INFORMATION
            ==================================================== --}}
            <section class="overflow-hidden rounded-2xl border
                            border-gray-100 bg-white shadow-sm">

                <div class="flex items-center gap-3 border-b
                            border-gray-100 p-5">

                    <div class="flex h-10 w-10 items-center justify-center
                                rounded-xl bg-emerald-50 text-emerald-600">

                        <i class="fas fa-wallet"></i>

                    </div>

                    <div class="flex-1">

                        <h3 class="font-bold text-gray-900">
                            Payroll Information
                        </h3>

                        <p class="mt-1 text-xs text-gray-500">
                            Payment and banking details
                        </p>

                    </div>

                    <i class="fas fa-lock text-sm text-gray-400"
                       title="Sensitive information"></i>

                </div>


                <div class="space-y-5 p-5">

                    <div>

                        <p class="text-sm text-gray-500">
                            Bank Name
                        </p>

                        <p class="mt-2 font-semibold text-gray-900">
                            {{ $payroll?->bank_name ?: 'Not provided' }}
                        </p>

                    </div>


                    <div>

                        <p class="text-sm text-gray-500">
                            Account Number
                        </p>

                        <p class="mt-2 break-all font-mono text-sm
                                  font-semibold text-gray-900">

                            {{ $maskValue($accountNumber, 4) }}

                        </p>

                    </div>


                    <div>

                        <p class="text-sm text-gray-500">
                            Sort Code
                        </p>

                        <p class="mt-2 font-mono text-sm
                                  font-semibold text-gray-900">

                            {{ $maskValue($sortCode, 2) }}

                        </p>

                    </div>


                    <div>

                        <p class="text-sm text-gray-500">
                            UTR
                        </p>

                        <p class="mt-2 break-all font-mono text-sm
                                  font-semibold text-gray-900">

                            {{ $maskValue($utr, 3) }}

                        </p>

                    </div>


                    <div>

                        <p class="text-sm text-gray-500">
                            Payment Type
                        </p>

                        <p class="mt-2 font-semibold text-gray-900">
                            {{ $payroll?->payment_type ?: 'Not specified' }}
                        </p>

                    </div>


                    <div class="rounded-xl border border-gray-200
                                bg-gray-50 p-3">

                        <p class="text-xs leading-5 text-gray-600">

                            <i class="fas fa-shield-alt mr-1"></i>

                            Banking information is protected. To change your
                            payment details, contact your administrator.

                        </p>

                    </div>

                </div>

            </section>


            
            {{-- ====================================================
                DOCUMENTS
            ==================================================== --}}
            <section class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">

                <div class="flex items-center gap-3 border-b border-gray-100 p-5">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                        <i class="fas fa-folder-open"></i>
                    </div>

                    <div>
                        <h3 class="font-bold text-gray-900">My Documents</h3>
                        <p class="mt-1 text-xs text-gray-500">
                            Upload and track your employee documents
                        </p>
                    </div>
                </div>

                <div class="p-5">

                    @php
                        $requiredDocumentTypes = [
                            'Passport',
                            'NI Proof',
                            'DBS',
                            'SIA Licence',
                            'Certificates',
                        ];

                        $documentsByType = $employee->documents->keyBy('document_type');
                    @endphp

                    @if(session('success'))
                        <div class="mb-5 flex items-start gap-3 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-700">
                            <i class="fas fa-check-circle mt-0.5"></i>
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-5 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                            <i class="fas fa-exclamation-circle mt-0.5"></i>
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                            <p class="font-semibold">Please correct the following:</p>
                            <ul class="mt-2 list-inside list-disc space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="space-y-4">

                        @foreach($requiredDocumentTypes as $documentType)

                            @php
                                $document = $documentsByType->get($documentType);

                                $rawStatus = strtolower(trim((string) (
                                    $document?->status
                                    ?: $document?->verification_status
                                    ?: 'pending'
                                )));

                                $documentStatus = match ($rawStatus) {
                                    'approved', 'verified' => 'approved',
                                    'rejected' => 'rejected',
                                    default => $document ? 'pending' : 'not_uploaded',
                                };

                                $statusClasses = match ($documentStatus) {
                                    'approved' => 'bg-green-50 text-green-700 ring-green-200',
                                    'rejected' => 'bg-red-50 text-red-700 ring-red-200',
                                    'pending' => 'bg-amber-50 text-amber-700 ring-amber-200',
                                    default => 'bg-gray-100 text-gray-600 ring-gray-200',
                                };

                                $statusLabel = match ($documentStatus) {
                                    'approved' => 'Approved',
                                    'rejected' => 'Rejected',
                                    'pending' => 'Pending review',
                                    default => 'Not uploaded',
                                };

                                $canUpload = !$document || in_array(
                                    $documentStatus,
                                    ['rejected'],
                                    true
                                );
                            @endphp

                            <div class="rounded-xl border border-gray-100 p-4">

                                <div class="flex flex-col gap-4 sm:flex-row sm:items-start">

                                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl
                                        {{ $documentStatus === 'approved' ? 'bg-green-50 text-green-600' : '' }}
                                        {{ $documentStatus === 'rejected' ? 'bg-red-50 text-red-600' : '' }}
                                        {{ $documentStatus === 'pending' ? 'bg-amber-50 text-amber-600' : '' }}
                                        {{ $documentStatus === 'not_uploaded' ? 'bg-gray-100 text-gray-500' : '' }}">
                                        <i class="fas fa-file-alt text-lg"></i>
                                    </div>

                                    <div class="min-w-0 flex-1">

                                        <div class="flex flex-wrap items-center gap-2">
                                            <h4 class="text-sm font-bold text-gray-900">
                                                {{ $documentType }}
                                            </h4>

                                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1 {{ $statusClasses }}">
                                                @if($documentStatus === 'approved')
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                @elseif($documentStatus === 'rejected')
                                                    <i class="fas fa-times-circle mr-1"></i>
                                                @elseif($documentStatus === 'pending')
                                                    <i class="fas fa-clock mr-1"></i>
                                                @else
                                                    <i class="fas fa-upload mr-1"></i>
                                                @endif

                                                {{ $statusLabel }}
                                            </span>
                                        </div>

                                        @if($document)

                                            <p class="mt-1 text-xs text-gray-500">
                                                Submitted {{ $document->created_at?->format('d M Y') ?? 'Date unavailable' }}
                                            </p>

                                            @if($document->expiry_date)
                                                <p class="mt-2 text-xs text-gray-500">
                                                    <i class="fas fa-calendar-alt mr-1"></i>
                                                    Expires {{ \Carbon\Carbon::parse($document->expiry_date)->format('d M Y') }}
                                                </p>
                                            @endif

                                            @if($documentStatus === 'rejected')
                                                <div class="mt-3 rounded-lg border border-red-100 bg-red-50 p-3">
                                                    <p class="text-xs font-semibold text-red-700">
                                                        <i class="fas fa-info-circle mr-1"></i>
                                                        Reason for rejection
                                                    </p>

                                                    <p class="mt-1 text-xs leading-5 text-red-600">
                                                        {{ $document->rejection_reason ?: 'Please contact an administrator for further details.' }}
                                                    </p>
                                                </div>
                                            @elseif($documentStatus === 'pending')
                                                <p class="mt-2 text-xs text-amber-700">
                                                    Your document is awaiting administrator review.
                                                </p>
                                            @elseif($documentStatus === 'approved')
                                                <p class="mt-2 text-xs text-green-700">
                                                    Your document has been approved.
                                                </p>
                                            @endif

                                        @else
                                            <p class="mt-1 text-xs text-gray-500">
                                                You have not uploaded this document yet.
                                            </p>
                                        @endif

                                    </div>

                                    @if($canUpload)
                                        <button
                                            type="button"
                                            onclick="document.getElementById('upload-document-{{ $loop->index }}').classList.toggle('hidden')"
                                            class="inline-flex shrink-0 items-center justify-center gap-2 rounded-lg bg-indigo-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-indigo-700"
                                        >
                                            <i class="fas fa-upload"></i>
                                            {{ $documentStatus === 'rejected' ? 'Upload replacement' : 'Upload document' }}
                                        </button>
                                    @endif

                                </div>

                                @if($canUpload)
                                    <div
                                        id="upload-document-{{ $loop->index }}"
                                        class="mt-4 hidden rounded-xl border border-indigo-100 bg-indigo-50/50 p-4"
                                    >
                                        <form
                                            action="{{ route('staff.profile.documents.upload') }}"
                                            method="POST"
                                            enctype="multipart/form-data"
                                            class="space-y-4"
                                        >
                                            @csrf

                                            <input
                                                type="hidden"
                                                name="document_type"
                                                value="{{ $documentType }}"
                                            >

                                            <div>
                                                <label class="mb-2 block text-xs font-semibold text-gray-700">
                                                    Select document
                                                </label>

                                                <input
                                                    type="file"
                                                    name="document"
                                                    accept=".jpg,.jpeg,.png,.pdf"
                                                    required
                                                    class="block w-full rounded-lg border border-gray-200 bg-white text-sm text-gray-700 file:mr-4 file:border-0 file:bg-gray-100 file:px-4 file:py-2.5 file:text-xs file:font-semibold file:text-gray-700 hover:file:bg-gray-200"
                                                >

                                                <p class="mt-1 text-xs text-gray-500">
                                                    JPG, JPEG, PNG or PDF. Maximum size: 2 MB.
                                                </p>
                                            </div>

                                            <div>
                                                <label class="mb-2 block text-xs font-semibold text-gray-700">
                                                    Expiry date <span class="font-normal text-gray-400">(optional)</span>
                                                </label>

                                                <input
                                                    type="date"
                                                    name="expiry_date"
                                                    value="{{ $document?->expiry_date?->format('Y-m-d') }}"
                                                    min="{{ now()->toDateString() }}"
                                                    class="block w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-700 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100"
                                                >
                                            </div>

                                            <div class="flex flex-wrap items-center gap-3">
                                                <button
                                                    type="submit"
                                                    class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-xs font-semibold text-white transition hover:bg-indigo-700"
                                                >
                                                    <i class="fas fa-cloud-upload-alt"></i>
                                                    Submit document
                                                </button>

                                                <button
                                                    type="button"
                                                    onclick="document.getElementById('upload-document-{{ $loop->index }}').classList.add('hidden')"
                                                    class="rounded-lg px-3 py-2.5 text-xs font-semibold text-gray-600 transition hover:bg-gray-100"
                                                >
                                                    Cancel
                                                </button>
                                            </div>

                                        </form>
                                    </div>
                                @endif

                            </div>

                        @endforeach

                    </div>

                    <div class="mt-5 flex items-start gap-3 rounded-xl border border-blue-100 bg-blue-50 p-4">
                        <i class="fas fa-shield-alt mt-0.5 text-blue-600"></i>
                        <p class="text-xs leading-5 text-blue-700">
                            Submitted documents are reviewed by an administrator. Rejected documents can be replaced.
                            Approved documents cannot be replaced here; contact an administrator if an update is required.
                        </p>
                    </div>

                </div>
            </section>


            {{-- ====================================================
                 PROFILE SUPPORT
            ==================================================== --}}
            <div class="rounded-2xl bg-gray-900 p-5 text-white shadow-sm">

                <div class="flex items-start gap-3">

                    <div class="flex h-10 w-10 shrink-0 items-center
                                justify-center rounded-xl bg-white/10">

                        <i class="fas fa-headset"></i>

                    </div>

                    <div>

                        <h3 class="font-semibold">
                            Need to update other details?
                        </h3>

                        <p class="mt-2 text-sm leading-6 text-gray-300">

                            Contact your administrator to change your name,
                            employment details, work eligibility, or payroll
                            information.

                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection