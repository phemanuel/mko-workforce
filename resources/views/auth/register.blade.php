@extends('layouts.app')

@section('content')

<div class="min-h-screen flex items-center justify-center bg-gray-100 px-4 py-10">

    <div class="bg-white w-full max-w-lg rounded-2xl shadow-lg p-8">

        <div class="text-center mb-8">

            <h2 class="text-3xl font-bold text-blue-700">
                Staff Registration
            </h2>

            <p class="text-gray-500 mt-2">
                Join the MKO Workforce Platform
            </p>

        </div>

        <form action="/register" method="POST">

            @csrf

            <!-- NAME -->
            <div class="mb-5">

                <label class="block mb-2 text-sm font-medium">
                    Full Name
                </label>

                <input type="text"
                       name="name"
                       value="{{ old('name') }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none"
                       required>

            </div>

            <!-- EMAIL -->
            <div class="mb-5">

                <label class="block mb-2 text-sm font-medium">
                    Email Address
                </label>

                <input type="email"
                       name="email"
                       value="{{ old('email') }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none"
                       required>

            </div>

            <!-- PASSWORD -->
            <div class="mb-5">

                <label class="block mb-2 text-sm font-medium">
                    Password
                </label>

                <input type="password"
                       name="password"
                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none"
                       required>

            </div>

            <!-- CONFIRM PASSWORD -->
            <div class="mb-6">

                <label class="block mb-2 text-sm font-medium">
                    Confirm Password
                </label>

                <input type="password"
                       name="password_confirmation"
                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none"
                       required>

            </div>

            <!-- BUTTON -->
            <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-semibold transition">
                Create Account
            </button>

        </form>

        <div class="text-center mt-6 text-sm text-gray-600">
            Already registered?

            <a href="{{ route('login') }}"
               class="text-blue-600 hover:underline">
                Login
            </a>
        </div>

    </div>

</div>

@endsection