@extends('layouts.login')

@section('title', 'Verify Email - MKO Workforce')

@section('content')

<div class="min-h-screen flex">

    <!-- LEFT SIDE (same branding image) -->
    <div class="hidden md:block w-1/2 relative">

        <img src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d"
             class="absolute inset-0 w-full h-full object-cover">

        <div class="absolute inset-0 bg-black/70"></div>

        <div class="absolute inset-0 flex items-center justify-center text-center px-10">

            <div>
                <img src="{{ asset('login/logo-white.png') }}" width="276" height="136" class="mx-auto">

                <p class="text-gray-300 mt-4 text-lg">
                    Recruitment • Compliance • Workforce Management
                </p>

                <div class="mt-6 w-20 h-1 bg-red-600 mx-auto"></div>
            </div>

        </div>

    </div>

    <!-- RIGHT SIDE -->
    <div class="w-full md:w-1/2 flex items-center justify-center bg-white px-6">

        <div class="w-full max-w-md text-center">

            <!-- ICON -->
            <div class="mb-6">
                <div class="mx-auto w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                    ✔
                </div>
            </div>

            <!-- TITLE -->
            <h2 class="text-2xl font-bold text-black">
                Verify Your Email
            </h2>

            <p class="text-gray-600 mt-3">
                We’ve sent a verification link to your email address.
                Please check your inbox and click the link to continue.
            </p>

            <!-- RESEND -->
            <form method="POST" action="{{ route('verification.send') }}" class="mt-6">
                @csrf

                <button class="w-full bg-red-600 hover:bg-red-700 text-white py-3 rounded-lg">
                    Resend Email
                </button>
            </form>

            <!-- BACK TO LOGIN -->
            <div class="mt-6 text-sm">
                <a href="/staff-login" class="text-red-600 font-semibold">
                    Back to Login
                </a>
            </div>

        </div>

    </div>

</div>

@endsection