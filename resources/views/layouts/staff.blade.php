<!DOCTYPE html>
<html>
<head>
    <title>MKO Staff Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<header class="bg-white border-b px-4 md:px-6 py-4 flex justify-between items-center">

    <!-- MOBILE MENU BUTTON -->
    <button class="md:hidden text-black text-2xl">
        ☰
    </button>

    <h2 class="font-semibold text-gray-700 text-sm md:text-base">
        @yield('page-title')
    </h2>

    <span class="text-xs md:text-sm bg-black text-white px-3 py-1 rounded-full">
        {{ strtoupper(auth()->user()->role) }}
    </span>

</header>
<body class="bg-gray-100 font-sans">

<div class="flex h-screen overflow-hidden">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-black text-white flex flex-col">

        <!-- BRAND -->
        <div class="p-6 border-b border-gray-800">
            <h1 class="text-xl font-bold text-red-500">
                MY WORKSPACE
            </h1>
            <p class="text-xs text-gray-400 mt-1">
                Staff Portal
            </p>
        </div>

        <!-- NAV -->
        <nav class="flex-1 p-4 space-y-2 text-sm">

            <a href="/staff/dashboard" class="block px-4 py-2 rounded hover:bg-gray-800">
                📊 Dashboard
            </a>

            <a href="/staff/shifts" class="block px-4 py-2 rounded hover:bg-gray-800">
                📅 Available Shifts
            </a>

            <a href="/staff/my-shifts" class="block px-4 py-2 rounded hover:bg-gray-800">
                🧾 My Shifts
            </a>

            <a href="/staff/attendance" class="block px-4 py-2 rounded hover:bg-gray-800">
                ⏱ Attendance
            </a>

            <a href="/staff/payments" class="block px-4 py-2 rounded hover:bg-gray-800">
                💰 Payments
            </a>

            <a href="/staff/profile" class="block px-4 py-2 rounded hover:bg-gray-800">
                👤 Profile
            </a>

        </nav>

        <!-- USER -->
        <div class="p-4 border-t border-gray-800">

            <p class="text-sm text-gray-300">{{ auth()->user()->name }}</p>
            <p class="text-xs text-gray-500">Staff Member</p>

            <form method="POST" action="/logout" class="mt-3">
                @csrf
                <button class="w-full bg-red-600 py-2 rounded text-sm">
                    Logout
                </button>
            </form>

        </div>

    </aside>

    <!-- MAIN -->
    <div class="flex-1 flex flex-col">

        <!-- TOP BAR -->
        <header class="bg-white border-b px-6 py-4 flex justify-between items-center">

            <h2 class="font-semibold text-gray-700">
                @yield('page-title', 'Staff Dashboard')
            </h2>

            <span class="text-sm bg-red-600 text-white px-3 py-1 rounded-full">
                STAFF
            </span>

        </header>

        <!-- CONTENT -->
        <main class="p-6 overflow-y-auto">

            @yield('content')

        </main>

    </div>

</div>

</body>
</html>