<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'MKO Workforce')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        <style>
    .input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 6px;
        outline: none;
    }

    .input:focus {
        border-color: #ef4444;
    }
</style>
    </style>
</head>

<body class="bg-gray-100 font-sans">

<div class="flex flex-col md:flex-row h-screen overflow-hidden">

    <!-- SIDEBAR -->
    <aside class="w-full md:w-72 bg-black text-white flex flex-col">

        <!-- BRAND -->
        <div class="p-6 border-b border-gray-800">
            <h1 class="text-xl font-bold text-red-500">
                MKO WORKFORCE
            </h1>
            <p class="text-xs text-gray-400 mt-1">
                @yield('role-label', 'System')
            </p>
        </div>

        <!-- NAV -->
        <nav class="flex-1 p-4 space-y-2 text-sm">
            @yield('sidebar')
        </nav>

        <!-- USER -->
        <div class="p-4 border-t border-gray-800">
            <p class="text-sm text-gray-300">{{ auth()->user()->name }}</p>

            <form method="POST" action="/logout" class="mt-3">
                @csrf
                <button class="w-full bg-red-600 py-2 rounded text-sm">
                    Logout
                </button>
            </form>
        </div>

    </aside>

    <!-- MAIN AREA -->
    <div class="flex-1 flex flex-col">

        <!-- TOP BAR -->
<header class="bg-white border-b px-4 md:px-6 py-4 flex justify-between items-center">

    <!-- LEFT: PAGE TITLE -->
    <h2 class="font-semibold text-gray-700 text-sm md:text-base">
        @yield('page-title', 'Dashboard')
    </h2>

    <!-- CENTER: DATE + TIME (DESKTOP ONLY) -->
    <div class="hidden md:flex flex-col text-center text-xs text-gray-600">

        <span class="font-medium text-gray-800">
            {{ now()->timezone(auth()->user()->timezone ?? config('app.timezone'))->format('l, d F Y') }}
        </span>

        <span class="text-gray-500">
            {{ now()->timezone(auth()->user()->timezone ?? config('app.timezone'))->format('h:i A') }}
        </span>

    </div>

    <!-- RIGHT: USER MENU -->
    <div class="relative" id="userMenuWrapper">

        <!-- BUTTON -->
        <button id="userMenuBtn"
            class="text-xs md:text-sm bg-black text-white px-3 py-2 rounded-full flex items-center gap-2">

            <span>{{ auth()->user()->name }}</span>

            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 9l-7 7-7-7" />
            </svg>

        </button>

        <!-- DROPDOWN -->
        <div id="userDropdown"
            class="hidden absolute right-0 mt-2 w-48 bg-white border rounded-lg shadow-lg z-50">

            <a href="/profile"
               class="block px-4 py-2 text-sm hover:bg-gray-100">
                👤 Profile
            </a>

            <a href="/settings"
               class="block px-4 py-2 text-sm hover:bg-gray-100">
                ⚙ Settings
            </a>

            <div class="border-t"></div>

            <form method="POST" action="/logout">
                @csrf
                <button class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                    🚪 Logout
                </button>
            </form>

        </div>

    </div>

</header>

        <!-- CONTENT -->
        <main class="p-4 md:p-6 overflow-y-auto">
            @yield('content')
        </main>

    </div>

</div>

</body>
</html>
<script>
    const btn = document.getElementById('userMenuBtn');
    const dropdown = document.getElementById('userDropdown');
    const wrapper = document.getElementById('userMenuWrapper');

    btn?.addEventListener('click', (e) => {
        e.stopPropagation();
        dropdown.classList.toggle('hidden');
    });

    document.addEventListener('click', () => {
        dropdown?.classList.add('hidden');
    });
</script>
