@extends('layouts.login')

@section('title', 'MKO Workforce :: Register')

@section('content')

<div class="min-h-screen flex">

    <!-- LEFT SIDE (same as login) -->
    <div class="hidden md:block w-1/2 relative">

        <img src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d"
             class="absolute inset-0 w-full h-full object-cover"
             alt="Recruitment">

        <div class="absolute inset-0 bg-black/70"></div>

        <div class="absolute inset-0 flex items-center justify-center text-center px-10">

            <div>
                <div class="flex justify-center">
                    <img src="{{ asset('login/logo-white.png') }}" width="276" height="136">
                </div>

                <p class="text-gray-300 mt-4 text-lg">
                    Join MKO Workforce Platform
                </p>

                <div class="mt-6 w-20 h-1 bg-red-600 mx-auto"></div>
            </div>

        </div>

    </div>

    <!-- RIGHT SIDE -->
    <div class="w-full md:w-1/2 flex items-center justify-center bg-white px-6">

        <div class="w-full max-w-md">
{{-- ALERT MESSAGES --}}
@if(session('success'))

    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative">
        {{ session('success') }}
    </div>

@endif

@if(session('error'))

    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative">
        {{ session('error') }}
    </div>

@endif

@if($errors->any())

    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">

        <ul class="list-disc pl-5 text-sm space-y-1">

            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach

        </ul>

    </div>

@endif
            <!-- HEADER -->
            <div class="text-center mb-8">

                <h2 class="text-3xl font-bold text-black">
                    Registration
                </h2>

                <p class="text-gray-500 mt-2">
                    Create your applicant account
                </p>

            </div>

            <!-- FORM -->
            <form method="POST" action="{{ route('register.save') }}">

                @csrf

                <!-- NAME -->
                <div class="mb-4">
                    <label class="text-sm">Full Name</label>
                    <input type="text" name="name"
                           class="w-full mt-2 px-4 py-3 border rounded-lg" required>
                </div>

                <!-- EMAIL -->
                <div class="mb-4">
                    <label class="text-sm">Email</label>
                    <input type="email" name="email"
                           class="w-full mt-2 px-4 py-3 border rounded-lg" required>
                </div>

                <!-- PHONE -->
                <div class="mb-4">
                    <label class="text-sm">Phone Number</label>
                    <input type="text" name="phone_no"
                           class="w-full mt-2 px-4 py-3 border rounded-lg" required>
                </div>

                <div class="mb-4">
                    <label class="text-sm">Password</label>
                <!-- PASSWORD -->
                <div class="relative">

    <input
        id="registerPassword"
        type="password"
        name="password"
        class="w-full mt-2 px-4 py-3 pr-12 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"
        required
    >

    <button
        type="button"
        onclick="togglePassword('registerPassword', this)"
        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-black p-2 rounded-full">

        <!-- EYE ICON -->
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 eye-open" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7
                -1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
        </svg>

    </button>

</div>
</div>
                <!-- CONFIRM PASSWORD -->
                 <div class="mb-4">
                    <label class="text-sm">Confirm Password</label>
                <div class="relative">

    <input
        id="registerPasswordConfirm"
        type="password"
        name="password_confirmation"
        class="w-full mt-2 px-4 py-3 pr-12 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"
        required
    >

    <button
        type="button"
        onclick="togglePassword('registerPasswordConfirm', this)"
        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-black p-2 rounded-full">

        <!-- EYE ICON -->
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 eye-open" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7
                -1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
        </svg>

    </button>

</div>
</div>
                <!-- BUTTON -->
                <button class="w-full bg-red-600 hover:bg-red-700 text-white py-3 rounded-lg font-semibold">
                    Create Account
                </button>

            </form>

            <!-- LOGIN LINK -->
            <p class="text-center text-sm text-gray-500 mt-6">
                Already have an account?
                <a href="{{route('login')}}" class="text-red-600 font-semibold">
                    Login
                </a>
            </p>

        </div>

    </div>

</div>

@endsection