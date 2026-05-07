@extends('layouts.app')

@section('content')

<!-- NAVBAR -->
<nav class="bg-white shadow-sm">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">

        <div>
            <h1 class="text-2xl font-bold text-blue-700">
                MKO Workforce
            </h1>
        </div>

        <div class="space-x-4">
            <a href="{{ route('login') }}"
               class="text-gray-700 hover:text-blue-600 font-medium">
                Login
            </a>

            <a href="{{ route('register') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg transition">
                Register
            </a>
        </div>

    </div>
</nav>

<!-- HERO -->
<section class="bg-gradient-to-r from-blue-700 to-blue-500 text-white">

    <div class="max-w-7xl mx-auto px-6 py-24 grid md:grid-cols-2 gap-12 items-center">

        <!-- LEFT -->
        <div>
            <h1 class="text-5xl font-extrabold leading-tight mb-6">
                Professional Workforce Management & Recruitment
            </h1>

            <p class="text-lg text-blue-100 mb-8 leading-relaxed">
                A complete workforce platform for recruitment,
                compliance, attendance tracking, payroll control,
                and operational efficiency.
            </p>

            <div class="flex gap-4">

                <a href="{{ route('register') }}"
                   class="bg-white text-blue-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                    Apply Now
                </a>

                <a href="{{ route('login') }}"
                   class="border border-white px-6 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-700 transition">
                    Staff Login
                </a>

            </div>
        </div>

        <!-- RIGHT -->
        <div class="hidden md:block">
            <img src="https://images.unsplash.com/photo-1521791136064-7986c2920216?q=80&w=1200"
                 alt="Workforce"
                 class="rounded-2xl shadow-2xl">
        </div>

    </div>

</section>

<!-- FEATURES -->
<section class="py-20 bg-white">

    <div class="max-w-7xl mx-auto px-6">

        <div class="text-center mb-14">
            <h2 class="text-3xl font-bold mb-4">
                Workforce Solutions Built for Compliance
            </h2>

            <p class="text-gray-600 max-w-3xl mx-auto">
                MKO Workforce helps organisations streamline recruitment,
                monitor attendance, manage shifts, and ensure payroll accuracy.
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">

            <!-- CARD -->
            <div class="bg-gray-50 rounded-2xl p-8 shadow-sm hover:shadow-md transition">

                <div class="w-14 h-14 bg-blue-100 text-blue-700 rounded-xl flex items-center justify-center mb-5 text-2xl">
                    👥
                </div>

                <h3 class="text-xl font-bold mb-3">
                    Recruitment & Vetting
                </h3>

                <p class="text-gray-600 leading-relaxed">
                    Efficient onboarding with secure document verification
                    and compliance approval workflows.
                </p>

            </div>

            <!-- CARD -->
            <div class="bg-gray-50 rounded-2xl p-8 shadow-sm hover:shadow-md transition">

                <div class="w-14 h-14 bg-green-100 text-green-700 rounded-xl flex items-center justify-center mb-5 text-2xl">
                    📍
                </div>

                <h3 class="text-xl font-bold mb-3">
                    GPS Attendance Tracking
                </h3>

                <p class="text-gray-600 leading-relaxed">
                    Real-time attendance tracking with geo-location verification
                    and compliance monitoring.
                </p>

            </div>

            <!-- CARD -->
            <div class="bg-gray-50 rounded-2xl p-8 shadow-sm hover:shadow-md transition">

                <div class="w-14 h-14 bg-purple-100 text-purple-700 rounded-xl flex items-center justify-center mb-5 text-2xl">
                    💰
                </div>

                <h3 class="text-xl font-bold mb-3">
                    Payroll Control
                </h3>

                <p class="text-gray-600 leading-relaxed">
                    Prevent payroll discrepancies with approval-based overtime
                    and shift validation systems.
                </p>

            </div>

        </div>

    </div>

</section>

<!-- CTA -->
<section class="bg-gray-900 text-white py-20">

    <div class="max-w-4xl mx-auto text-center px-6">

        <h2 class="text-4xl font-bold mb-5">
            Join the MKO Workforce Platform
        </h2>

        <p class="text-gray-300 text-lg mb-8">
            Register today and become part of a secure,
            professional workforce management system.
        </p>

        <a href="{{ route('register') }}"
           class="bg-blue-600 hover:bg-blue-700 px-8 py-4 rounded-xl font-semibold inline-block transition">
            Create Account
        </a>

    </div>

</section>

<!-- FOOTER -->
<footer class="bg-white py-6 border-t">

    <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center">

        <p class="text-gray-500 text-sm">
            © {{ date('Y') }} MKO Workforce. All rights reserved.
        </p>

        <div class="flex gap-5 mt-4 md:mt-0 text-sm text-gray-500">
            <span>GDPR Compliant</span>
            <span>Secure Workforce Platform</span>
        </div>

    </div>

</footer>

@endsection