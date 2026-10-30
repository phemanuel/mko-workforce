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

            <section class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">

                {{-- SUCCESS MESSAGE --}}
                @if (session('success'))
                    <div class="mx-5 mt-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                        <div class="flex items-start gap-2">
                            <i class="fas fa-check-circle mt-0.5"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                {{-- VALIDATION ERRORS --}}
                @if ($errors->any())
                    <div class="mx-5 mt-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        <div class="flex items-start gap-2">
                            <i class="fas fa-exclamation-circle mt-0.5"></i>

                            <div>
                                <p class="font-semibold">Please correct the following:</p>

                                <ul class="mt-2 list-inside list-disc space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif


                {{-- ====================================================
                    EDIT PAYMENT INFORMATION
                ==================================================== --}}

                <details
                    class="group"
                    @if ($errors->hasAny([
                        'payment_type',
                        'bank_name',
                        'account_number',
                        'sort_code',
                        'utr'
                    ]))
                        open
                    @endif
                >
                    {{-- SUMMARY / CLICKABLE HEADER --}}
                    <summary class="flex cursor-pointer list-none items-center gap-3 border-b border-gray-100 p-5">

                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                            <i class="fas fa-wallet"></i>
                        </span>

                        <span class="min-w-0 flex-1">
                            <span class="block font-bold text-gray-900">
                                Payroll Information
                            </span>

                            <span class="mt-1 block text-xs text-gray-500">
                                Payment and banking details
                            </span>
                        </span>

                        <span class="flex shrink-0 items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                            <i class="fas fa-edit"></i>
                            <span>Edit Information</span>
                            <i class="fas fa-chevron-down text-xs transition-transform group-open:rotate-180"></i>
                        </span>

                        <i class="fas fa-lock text-sm text-gray-400" title="Sensitive information"></i>

                    </summary>

                        <i class="fas fa-lock text-sm text-gray-400" title="Sensitive information"></i>                  


                    {{-- EDIT FORM --}}
                    <div class="border-b border-gray-100 bg-gray-50/50 p-5">

                        <form
                            id="payrollInformationForm"
                            method="POST"
                            action="{{ route('staff.payroll-information.update') }}"
                            class="space-y-5"
                        >
                            @csrf
                            @method('PUT')


                            {{-- PAYMENT TYPE --}}
                            <div>
                                <label
                                    for="payroll_payment_type"
                                    class="mb-2 block text-sm font-semibold text-gray-700"
                                >
                                    Payment Type
                                    <span class="text-red-500">*</span>
                                </label>

                                <select
                                     id="paymentType"
                                    name="payment_type"
                                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100"
                                    required
                                >
                                    <option value="">Select payment type</option>

                                    <option
                                        value="PAYE"
                                        @selected(old('payment_type', $payroll?->payment_type) === 'PAYE')
                                    >
                                        PAYE
                                    </option>

                                    <option
                                        value="Self-Employed"
                                        @selected(old('payment_type', $payroll?->payment_type) === 'Self-Employed')
                                    >
                                        Self-Employed
                                    </option>

                                    <option
                                        value="Contractor"
                                        @selected(old('payment_type', $payroll?->payment_type) === 'Contractor')
                                    >
                                        Contractor
                                    </option>
                                </select>

                                <p class="mt-1 text-xs text-gray-500">
                                    Select the payment arrangement that applies to you.
                                </p>
                            </div>


                            {{-- BANK NAME --}}
                            <div>
                                <label
                                    for="payroll_bank_name"
                                    class="mb-2 block text-sm font-semibold text-gray-700"
                                >
                                    Bank Name
                                </label>

                                <input
                                    type="text"
                                    id="payroll_bank_name"
                                    name="bank_name"
                                    value="{{ old('bank_name', $payroll?->bank_name) }}"
                                    maxlength="255"
                                    autocomplete="off"
                                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100"
                                    placeholder="Enter your bank name"
                                >
                            </div>


                            {{-- ACCOUNT NUMBER --}}
                            <div>
                                <label
                                    for="payroll_account_number"
                                    class="mb-2 block text-sm font-semibold text-gray-700"
                                >
                                    Account Number
                                </label>

                                <input
                                    type="text"
                                    id="payroll_account_number"
                                    name="account_number"
                                    value="{{ old('account_number') }}"
                                    maxlength="50"
                                    inputmode="numeric"
                                    autocomplete="off"
                                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 font-mono text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100"
                                    placeholder="Enter your account number"
                                >

                                <p class="mt-1 text-xs text-gray-500">
                                    Leave blank if you do not want to change your saved account number.
                                </p>
                            </div>


                            {{-- SORT CODE --}}
                            <div>
                                <label
                                    for="payroll_sort_code"
                                    class="mb-2 block text-sm font-semibold text-gray-700"
                                >
                                    Sort Code
                                </label>

                                <input
                                    type="text"
                                    id="payroll_sort_code"
                                    name="sort_code"
                                    value="{{ old('sort_code') }}"
                                    maxlength="20"
                                    autocomplete="off"
                                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 font-mono text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100"
                                    placeholder="Enter your sort code"
                                >

                                <p class="mt-1 text-xs text-gray-500">
                                    Leave blank if you do not want to change your saved sort code.
                                </p>
                            </div>


                            {{-- UTR: ONLY FOR SELF-EMPLOYED --}}
                            <div id="utrField"
                                class="{{ old('payment_type', $payroll?->payment_type) === 'Self-Employed' ? '' : 'hidden' }}">

                                <label for="utr" class="mb-2 block text-sm font-medium text-gray-700">
                                    Unique Taxpayer Reference (UTR)
                                    <span class="text-red-500">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="utr"
                                    id="utr"
                                    value="{{ old('utr') }}"
                                    maxlength="10"
                                    pattern="[0-9]{10}"
                                    placeholder="Enter your 10-digit UTR"
                                    class="..."
                                >

                                <p class="mt-1 text-xs text-gray-500">
                                    Required for self-employed payment. Leave blank to retain your existing UTR.
                                </p>

                                @error('utr')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>


                            {{-- ACTIONS --}}
                            <div class="flex flex-wrap justify-end gap-3 border-t border-gray-100 pt-5">

                                <a
                                    href="{{ route('staff.profile.index') }}"
                                    class="inline-flex items-center rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                                >
                                    Cancel
                                </a>

                                <button
                                    type="submit"
                                    id="savePayrollInformationButton"
                                    class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-700"
                                >
                                    <i class="fas fa-save"></i>
                                    <span>Save Changes</span>
                                </button>

                            </div>

                        </form>

                    </div>

                </details>


                {{-- ====================================================
                    READ-ONLY PAYMENT INFORMATION
                ==================================================== --}}

                <div
                    id="payrollInformationDisplay"
                    class="space-y-5 p-5"
                >

                    {{-- BANK NAME --}}
                    <div>
                        <p class="text-sm text-gray-500">Bank Name</p>

                        <p class="mt-2 font-semibold text-gray-900">
                            {{ $payroll?->bank_name ?: 'Not provided' }}
                        </p>
                    </div>


                    {{-- ACCOUNT NUMBER --}}
                    <div>
                        <p class="text-sm text-gray-500">Account Number</p>

                        <p class="mt-2 break-all font-mono text-sm font-semibold text-gray-900">
                            {{ $maskValue($accountNumber, 4) }}
                        </p>
                    </div>


                    {{-- SORT CODE --}}
                    <div>
                        <p class="text-sm text-gray-500">Sort Code</p>

                        <p class="mt-2 font-mono text-sm font-semibold text-gray-900">
                            {{ $maskValue($sortCode, 2) }}
                        </p>
                    </div>


                    {{-- UTR: ONLY FOR SELF-EMPLOYED --}}
                    @if (strtolower(trim($payroll?->payment_type ?? '')) === 'self-employed')
                        <div>
                            <p class="text-sm text-gray-500">UTR</p>

                            <p class="mt-2 break-all font-mono text-sm font-semibold text-gray-900">
                                {{ $maskValue($utr, 3) }}
                            </p>
                        </div>
                    @endif


                    {{-- PAYMENT TYPE --}}
                    <div>
                        <p class="text-sm text-gray-500">Payment Type</p>

                        <p class="mt-2 font-semibold text-gray-900">
                            {{ $payroll?->payment_type ?: 'Not specified' }}
                        </p>
                    </div>


                    {{-- SECURITY NOTICE --}}
                    <div class="rounded-xl border border-gray-200 bg-gray-50 p-3">
                        <p class="text-xs leading-5 text-gray-600">
                            <i class="fas fa-shield-alt mr-1"></i>

                            Your payment information is sensitive. Only update it
                            with accurate banking and payment details.
                        </p>
                    </div>

                </div>

            </section>


         
           
            {{-- ====================================================
                DOCUMENTS
            ==================================================== --}}

            <section class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">

                {{-- Section Header --}}
                <div class="flex items-center gap-3 border-b border-gray-100 p-5">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                        <i class="fas fa-folder-open"></i>
                    </div>

                    <div>
                        <h3 class="font-bold text-gray-900">My Documents</h3>
                        <p class="mt-1 text-xs text-gray-500">
                            Upload and track your employee documents and certifications.
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
                            'Other Certification',
                        ];

                        /*
                        |--------------------------------------------------------------------------
                        | Group employee documents by type
                        |--------------------------------------------------------------------------
                        | Existing "Certificates" records are displayed as "Other Certification".
                        | No database records are changed by this display mapping.
                        */

                        $employeeDocuments = $employee->documents->map(function ($document) {
                            if ($document->document_type === 'Certificates') {
                                $document->document_type = 'Other Certification';
                            }

                            return $document;
                        })->groupBy('document_type');

                        /*
                        |--------------------------------------------------------------------------
                        | Resolve status consistently
                        |--------------------------------------------------------------------------
                        | Supports both the newer status column and the legacy
                        | verification_status column.
                        */

                        $resolveDocumentStatus = function ($document) {
                            if (!$document) {
                                return 'not_uploaded';
                            }

                            $status = strtolower(trim((string) $document->status));
                            $verificationStatus = strtolower(
                                trim((string) $document->verification_status)
                            );

                            if (in_array($status, ['approved', 'verified'], true)) {
                                return 'approved';
                            }

                            if (in_array($status, ['rejected'], true)) {
                                return 'rejected';
                            }

                            if (in_array($verificationStatus, ['verified', 'approved'], true)) {
                                return 'approved';
                            }

                            if ($verificationStatus === 'rejected') {
                                return 'rejected';
                            }

                            return 'pending';
                        };

                        $documentStatusClasses = [
                            'approved' => 'bg-green-50 text-green-700 ring-green-200',
                            'rejected' => 'bg-red-50 text-red-700 ring-red-200',
                            'pending' => 'bg-amber-50 text-amber-700 ring-amber-200',
                            'not_uploaded' => 'bg-gray-100 text-gray-600 ring-gray-200',
                        ];

                        $documentStatusLabels = [
                            'approved' => 'Approved',
                            'rejected' => 'Rejected',
                            'pending' => 'Pending review',
                            'not_uploaded' => 'Not uploaded',
                        ];

                        $documentStatusIcons = [
                            'approved' => 'fas fa-check-circle',
                            'rejected' => 'fas fa-times-circle',
                            'pending' => 'fas fa-clock',
                            'not_uploaded' => 'fas fa-upload',
                        ];
                    @endphp

                    {{-- Success Message --}}
                    @if(session('success'))
                        <div class="mb-5 flex items-start gap-3 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-700">
                            <i class="fas fa-check-circle mt-0.5"></i>
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    {{-- Error Message --}}
                    @if(session('error'))
                        <div class="mb-5 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                            <i class="fas fa-exclamation-circle mt-0.5"></i>
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif

                    {{-- Validation Errors --}}
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
                                $isCertification = $documentType === 'Other Certification';

                                $documentsForType = $employeeDocuments->get(
                                    $documentType,
                                    collect()
                                );

                                /*
                                |--------------------------------------------------------------------------
                                | Standard document status
                                |--------------------------------------------------------------------------
                                | Standard types use one document record.
                                */

                                $document = $isCertification
                                    ? null
                                    : $documentsForType->first();

                                $documentStatus = $resolveDocumentStatus($document);

                                /*
                                |--------------------------------------------------------------------------
                                | Certification summary
                                |--------------------------------------------------------------------------
                                | Certifications can have several records with independent statuses.
                                */

                                $certificationStatuses = $isCertification
                                    ? $documentsForType->map(
                                        fn ($certification) => $resolveDocumentStatus($certification)
                                    )
                                    : collect();

                                $certificationCount = $documentsForType->count();

                                $approvedCertifications = $certificationStatuses
                                    ->filter(fn ($status) => $status === 'approved')
                                    ->count();

                                $pendingCertifications = $certificationStatuses
                                    ->filter(fn ($status) => $status === 'pending')
                                    ->count();

                                $rejectedCertifications = $certificationStatuses
                                    ->filter(fn ($status) => $status === 'rejected')
                                    ->count();

                                if ($isCertification) {
                                    if ($certificationCount === 0) {
                                        $documentStatus = 'not_uploaded';
                                        $statusLabel = 'Not uploaded';
                                    } elseif ($pendingCertifications > 0) {
                                        $documentStatus = 'pending';
                                        $statusLabel = $pendingCertifications . ' pending review';
                                    } elseif ($rejectedCertifications > 0) {
                                        $documentStatus = 'rejected';
                                        $statusLabel = $rejectedCertifications . ' rejected';
                                    } else {
                                        $documentStatus = 'approved';
                                        $statusLabel = 'All approved';
                                    }
                                } else {
                                    $statusLabel = $documentStatusLabels[$documentStatus];
                                }

                                $statusClasses = $documentStatusClasses[$documentStatus];

                                /*
                                |--------------------------------------------------------------------------
                                | Upload rules
                                |--------------------------------------------------------------------------
                                | Certifications can always receive additional uploads.
                                | Standard documents can be uploaded if missing or rejected.
                                */

                                $canUpload = $isCertification
                                    || !$document
                                    || $documentStatus === 'rejected';

                                $uploadFormId = 'upload-document-' . $loop->index;
                            @endphp

                            <div class="rounded-xl border border-gray-100 p-4">

                                {{-- Document Heading --}}
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
                                                <i class="{{ $documentStatusIcons[$documentStatus] }} mr-1"></i>
                                                {{ $statusLabel }}
                                            </span>

                                            @if($isCertification && $certificationCount > 0)
                                                <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600">
                                                    {{ $certificationCount }}
                                                    {{ \Illuminate\Support\Str::plural('file', $certificationCount) }}
                                                </span>
                                            @endif

                                        </div>

                                        @if($isCertification)

                                            <p class="mt-2 text-xs leading-5 text-gray-500">
                                                Upload additional professional qualifications, training certificates,
                                                and other relevant certifications. Each file is reviewed separately.
                                            </p>

                                        @else

                                            @if($document)
                                                <div class="mt-2 space-y-1">

                                                    <p class="break-all text-xs text-gray-500">
                                                        <i class="fas fa-paperclip mr-1"></i>
                                                        {{ basename($document->file_path) }}
                                                    </p>

                                                    @if($document->expiry_date)
                                                        <p class="text-xs text-gray-500">
                                                            <i class="fas fa-calendar-alt mr-1"></i>
                                                            Expiry:
                                                            {{ \Illuminate\Support\Carbon::parse($document->expiry_date)->format('d M Y') }}
                                                        </p>
                                                    @endif

                                                    @if($documentStatus === 'rejected')
                                                        <div class="mt-2 rounded-lg bg-red-50 p-3 text-xs text-red-700">
                                                            <p class="font-semibold">Reason for rejection</p>

                                                            <p class="mt-1">
                                                                {{ $document->rejection_reason ?: 'This document was rejected. Please upload a replacement.' }}
                                                            </p>
                                                        </div>
                                                    @endif

                                                </div>
                                            @else
                                                <p class="mt-2 text-xs leading-5 text-gray-500">
                                                    No document has been uploaded for this category yet.
                                                </p>
                                            @endif

                                        @endif

                                    </div>

                                    {{-- Upload Button --}}
                                    @if($canUpload)
                                        <button
                                            type="button"
                                            onclick="document.getElementById('{{ $uploadFormId }}').classList.toggle('hidden')"
                                            aria-controls="{{ $uploadFormId }}"
                                            aria-expanded="false"
                                            class="inline-flex shrink-0 items-center justify-center gap-2 rounded-lg bg-indigo-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-indigo-700"
                                        >
                                            <i class="fas fa-upload"></i>

                                            @if($isCertification)
                                                Add certification
                                            @elseif($documentStatus === 'rejected')
                                                Upload replacement
                                            @else
                                                Upload document
                                            @endif
                                        </button>
                                    @endif

                                </div>

                                {{-- Existing Certification Records --}}
                                @if($isCertification && $documentsForType->isNotEmpty())

                                    <div class="mt-4 space-y-3">

                                        @foreach($documentsForType as $certification)

                                            @php
                                                $certificationStatus = $resolveDocumentStatus($certification);

                                                $certificationStatusClasses = $documentStatusClasses[$certificationStatus];

                                                $expiryDate = $certification->expiry_date
                                                    ? \Illuminate\Support\Carbon::parse($certification->expiry_date)->format('d M Y')
                                                    : null;
                                            @endphp

                                            <div class="rounded-xl border border-gray-200 bg-gray-50/50 p-4">

                                                <div class="flex flex-wrap items-start justify-between gap-3">

                                                    <div class="min-w-0 flex-1">

                                                        <p class="break-all text-sm font-semibold text-gray-900">
                                                            <i class="fas fa-file-alt mr-1 text-gray-400"></i>
                                                            {{ basename($certification->file_path) }}
                                                        </p>

                                                        @if($expiryDate)
                                                            <p class="mt-1 text-xs text-gray-500">
                                                                Expiry: {{ $expiryDate }}
                                                            </p>
                                                        @endif

                                                    </div>

                                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold ring-1 {{ $certificationStatusClasses }}">
                                                        <i class="{{ $documentStatusIcons[$certificationStatus] }} mr-1"></i>
                                                        {{ $documentStatusLabels[$certificationStatus] }}
                                                    </span>

                                                </div>

                                                @if($certificationStatus === 'rejected')
                                                    <div class="mt-3 rounded-lg border border-red-100 bg-red-50 p-3 text-xs text-red-700">
                                                        <p class="font-semibold">Reason for rejection</p>

                                                        <p class="mt-1">
                                                            {{ $certification->rejection_reason ?: 'This certification was rejected.' }}
                                                        </p>
                                                    </div>
                                                @endif

                                            </div>

                                        @endforeach

                                    </div>

                                @endif

                                {{-- Upload Form --}}
                                @if($canUpload)

                                    <div
                                        id="{{ $uploadFormId }}"
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

                                            {{-- File Input --}}
                                            <div>

                                                <label class="mb-2 block text-xs font-semibold text-gray-700">
                                                    {{ $isCertification ? 'Select certification files' : 'Select document' }}
                                                </label>

                                                @if($isCertification)

                                                    <input
                                                        type="file"
                                                        name="documents[]"
                                                        multiple
                                                        required
                                                        accept=".jpg,.jpeg,.png,.pdf"
                                                        class="block w-full rounded-lg border border-gray-200 bg-white text-sm text-gray-700 file:mr-4 file:border-0 file:bg-gray-100 file:px-4 file:py-2.5 file:text-xs file:font-semibold file:text-gray-700 hover:file:bg-gray-200"
                                                    >

                                                    <p class="mt-1 text-xs text-gray-500">
                                                        Select up to 10 files per submission.
                                                        JPG, JPEG, PNG or PDF. Maximum size: 2 MB per file.
                                                    </p>

                                                @else

                                                    <input
                                                        type="file"
                                                        name="document"
                                                        required
                                                        accept=".jpg,.jpeg,.png,.pdf"
                                                        class="block w-full rounded-lg border border-gray-200 bg-white text-sm text-gray-700 file:mr-4 file:border-0 file:bg-gray-100 file:px-4 file:py-2.5 file:text-xs file:font-semibold file:text-gray-700 hover:file:bg-gray-200"
                                                    >

                                                    <p class="mt-1 text-xs text-gray-500">
                                                        JPG, JPEG, PNG or PDF. Maximum size: 2 MB.
                                                    </p>

                                                @endif

                                            </div>

                                            {{-- Expiry Date --}}
                                           
                                            <div>

                                                <label class="mb-2 block text-xs font-semibold text-gray-700">
                                                    Expiry date
                                                    <span class="font-normal text-gray-400">(optional)</span>
                                                </label>

                                                <input
                                                    type="date"
                                                    name="expiry_date"
                                                    value="{{ old('expiry_date') }}"
                                                    min="{{ now()->toDateString() }}"
                                                    class="block w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-700 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100"
                                                >

                                                @if($isCertification)
                                                    <p class="mt-1 text-xs text-gray-500">
                                                        This expiry date applies to every file in this submission.
                                                    </p>
                                                @endif

                                            </div>

                                            {{-- Actions --}}
                                            <div class="flex flex-wrap items-center gap-3">

                                                <button
                                                    type="submit"
                                                    class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-xs font-semibold text-white transition hover:bg-indigo-700"
                                                >
                                                    <i class="fas fa-cloud-upload-alt"></i>

                                                    {{ $isCertification ? 'Upload certifications' : 'Submit document' }}
                                                </button>

                                                <button
                                                    type="button"
                                                    onclick="document.getElementById('{{ $uploadFormId }}').classList.add('hidden')"
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

                    {{-- Information Notice --}}
                    <div class="mt-5 flex items-start gap-3 rounded-xl border border-blue-100 bg-blue-50 p-4">
                        <i class="fas fa-shield-alt mt-0.5 text-blue-600"></i>

                        <p class="text-xs leading-5 text-blue-700">
                            Submitted documents are reviewed by an administrator.
                            Rejected documents can be replaced, while additional certifications can be uploaded
                            without replacing existing ones. Approved standard documents cannot be replaced here;
                            contact an administrator if an update is required.
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const paymentType = document.getElementById('paymentType');
    const utrField = document.getElementById('utrField');

    if (!paymentType || !utrField) return;

    function toggleUtrField() {
        const isSelfEmployed = paymentType.value === 'Self-Employed';

        utrField.classList.toggle('hidden', !isSelfEmployed);
    }

    paymentType.addEventListener('change', toggleUtrField);

    // Set the correct visibility on initial page load.
    toggleUtrField();
});
</script>

@endsection