@extends('layouts.app')

@section('title', 'Login - MKO Workforce')

@section('content')

<div class="min-h-screen flex">

    <!-- LEFT SIDE IMAGE -->
    <div class="hidden md:block w-1/2 relative">

        <img src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d"
             class="absolute inset-0 w-full h-full object-cover"
             alt="Recruitment">

        <div class="absolute inset-0 bg-black/70"></div>

        <div class="absolute inset-0 flex items-center justify-center text-center px-10">

            <div>
                <h1 class="text-white text-4xl font-bold">
                    <div class="flex items-center justify-center">
                        <img src="{{ asset('login/logo-white.png') }}" width="276" height="136" alt="">
                    </div>
                </h1>

                <p class="text-gray-300 mt-4 text-lg">
                    Recruitment • Compliance • Workforce Management
                </p>

                <div class="mt-6 w-20 h-1 bg-red-600 mx-auto"></div>
            </div>

        </div>

    </div>

    <!-- RIGHT SIDE -->
    <div class="w-full md:w-1/2 flex items-center justify-center bg-white px-6">

        <div class="w-full max-w-md">

            <!-- LOGO AREA -->
            <div class="text-center mb-8">

                <!-- LOGO + TITLE -->
            <div class="flex flex-col items-center justify-center space-y-4">
                <!-- <div class="flex items-center justify-center">
                    <img src="{{ asset('login/logo-dark.png') }}" alt="">
                </div> -->

                <h2 class="text-3xl font-bold text-black">
                    Staff Login
                </h2>
            </div>

                <p class="text-gray-500 mt-2">
                    Access your workforce dashboard
                </p>

            </div>
             <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
            <!-- FORM -->
            <form method="POST" action="{{route('login.submit')}}">

                @csrf

                <!-- EMAIL -->
                <div class="mb-5">

                    <label class="text-sm font-medium text-gray-700">Email</label>

                    <input type="email"
                           name="email"
                           class="w-full mt-2 px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"
                           required>

                </div>

                <!-- PASSWORD -->
                <div class="mb-3">

                    <label class="text-sm font-medium text-gray-700">Password</label>

                    <input type="password"
                           name="password"
                           class="w-full mt-2 px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"
                           required>

                </div>

                <!-- FORGOT PASSWORD -->
                <div class="flex justify-end mb-6">

                    <a href="/forgot-password"
                       class="text-sm text-red-600 hover:underline">
                        Forgot Password?
                    </a>

                </div>

                <!-- BUTTON -->
                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white py-3 rounded-lg font-semibold transition">
                    Login
                </button>

            </form>

            <!-- REGISTER -->
            <p class="text-center text-sm text-gray-500 mt-6">
                New staff?

                <a href="/register" class="text-red-600 font-semibold hover:underline">
                    Create account
                </a>
            </p>

        </div>

    </div>

</div>

@endsection