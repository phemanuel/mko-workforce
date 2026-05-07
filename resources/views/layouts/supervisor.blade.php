<!DOCTYPE html>
<html>
<head>
    <title>MKO Supervisor Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-sans">

<!-- MAIN WRAPPER -->
<div class="flex flex-col md:flex-row h-screen overflow-hidden">

    <!-- SIDEBAR -->
    <aside class="w-full md:w-72 bg-black text-white flex flex-col">

        <!-- BRAND -->
        <div class="p-6 border-b border-gray-800">
            <h1 class="text-xl font-bold text-red-500">
                MKO WORKFORCE
            </h1>
            <p class="text-xs text-gray-400 mt-1">
                Supervisor Console
            </p>
        </div>

        <!-- NAV -->
        <nav class="flex-1 p-4 space-y-2 text-sm">

            <a href="/supervisor/dashboard" class="block px-4 py-2 rounded hover:bg-gray-800">
                📊 Dashboard
            </a>

            <a href="/supervisor/shifts" class="block px-4 py-2 rounded hover:bg-gray-800">
                📅 My Shifts
            </a>

            <a href="/supervisor/staff" class="block px-4 py-2 rounded hover:bg-gray-800">
                👥 Staff Monitoring
            </a>

            <a href="/supervisor/attendance" class="block px-4 py-2 rounded hover:bg-gray-800">
                ⏱ Attendance
            </a>

            <a href="/supervisor/incidents" class="block px-4 py-2 rounded hover:bg-gray-800">
                ⚠ Incidents
            </a>

            <a href="/supervisor/reports" class="block px-4 py-2 rounded hover:bg-gray-800">
                📄 Reports
            </a>

        </nav>

        <!-- USER FOOTER -->
        <div class="p-4 border-t border-gray-800">

            <p class="text-sm text-gray-300">{{ auth()->user()->name }}</p>
            <p class="text-xs text-gray-500">Supervisor</p>

            <form method="POST" action="/logout" class="mt-3">
                @csrf
                <button class="w-full bg-red-600 hover:bg-red-700 py-2 rounded text-sm">
                    Logout
                </button>
            </form>

        </div>

    </aside>

    <!-- MAIN AREA -->
    <div class="flex-1 flex flex-col">

        <!-- TOP BAR -->
        <header class="bg-white border-b px-4 md:px-6 py-4 flex justify-between items-center">

            <h2 class="font-semibold text-gray-700 text-sm md:text-base">
                @yield('page-title', 'Supervisor Dashboard')
            </h2>

            <span class="text-xs md:text-sm bg-black text-white px-3 py-1 rounded-full">
                SUPERVISOR
            </span>

        </header>

        <!-- CONTENT -->
        <main class="p-4 md:p-6 overflow-y-auto">

            @yield('content')

        </main>

    </div>

</div>

</body>
</html>