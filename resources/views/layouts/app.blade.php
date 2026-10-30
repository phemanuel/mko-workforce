<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'MKO Workforce')</title>

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Font Awesome --}}
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        referrerpolicy="no-referrer"
    >

    {{-- Favicon --}}
    <link
        href="{{ asset('login/favicon.png') }}"
        rel="shortcut icon"
        type="image/png"
    >

    <style>
        html {
            height: 100%;
            -webkit-text-size-adjust: 100%;
        }

        body {
            min-height: 100%;
            margin: 0;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        input,
        select,
        textarea {
            max-width: 100%;
        }

        input:not([type="checkbox"]):not([type="radio"]):not([type="file"]):not([type="range"]):not([type="color"]) {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            outline: none;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: #ef4444;
        }

        button,
        a {
            -webkit-tap-highlight-color: transparent;
        }

        /* Prevent long content from stretching the entire page. */
        main,
        main > * {
            min-width: 0;
        }

        /* Responsive tables */
        main table {
            max-width: 100%;
        }

        /* Keep the existing employee Documents tab fix. */
        #editModal:has(#tab-documents:not(.hidden)) #editEmployeeForm {
            display: none;
        }

        #editModal #tab-documents {
            min-height: 0;
        }

        /* Toast animation */
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.2s ease-out;
        }

        /* Respect devices that prefer reduced motion. */
        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                scroll-behavior: auto !important;
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</head>

<body class="bg-gray-100 font-sans text-gray-900 antialiased">

<div class="flex h-screen h-dvh overflow-hidden">

    {{-- =========================================================
         DESKTOP SIDEBAR
    ========================================================== --}}
    <aside
        id="desktopSidebar"
        class="hidden md:flex md:w-64 lg:w-72 shrink-0 flex-col bg-black text-white"
    >

        {{-- BRAND --}}
        <div class="shrink-0 border-b border-gray-800 px-6 py-6">
            <h1 class="text-xl font-bold tracking-tight text-red-500">
                MKO WORKFORCE
            </h1>

            <p class="mt-1 text-xs text-gray-400">
                @yield('role-label', 'System')
            </p>
        </div>

        {{-- NAVIGATION --}}
        <nav class="flex-1 space-y-2 overflow-y-auto p-4 text-sm">
            @yield('sidebar')
        </nav>

        {{-- USER / LOGOUT --}}
        <div class="shrink-0 border-t border-gray-800 p-4">
            <p class="truncate text-sm text-gray-300">
                {{ auth()->user()->name }}
            </p>

            <form method="POST" action="/logout" class="mt-3">
                @csrf

                <button
                    type="submit"
                    class="w-full rounded-lg bg-red-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2 focus:ring-offset-black"
                >
                    <i class="fa-solid fa-right-from-bracket mr-2"></i>
                    Logout
                </button>
            </form>
        </div>

    </aside>


    {{-- =========================================================
         MOBILE SIDEBAR OVERLAY
    ========================================================== --}}
    <div
        id="mobileSidebarOverlay"
        class="fixed inset-0 z-40 hidden bg-black/60 md:hidden"
        aria-hidden="true"
    ></div>


    {{-- =========================================================
         MOBILE SIDEBAR
    ========================================================== --}}
    <aside
        id="mobileSidebar"
        class="fixed inset-y-0 left-0 z-50 flex w-72 max-w-[85vw] -translate-x-full flex-col bg-black text-white shadow-2xl transition-transform duration-300 ease-in-out md:hidden"
        aria-label="Mobile navigation"
        aria-hidden="true"
        inert
    >

        {{-- MOBILE BRAND --}}
        <div class="flex shrink-0 items-center justify-between border-b border-gray-800 px-5 py-5">

            <div class="min-w-0">
                <h1 class="text-lg font-bold tracking-tight text-red-500">
                    MKO WORKFORCE
                </h1>

                <p class="mt-1 text-xs text-gray-400">
                    @yield('role-label', 'System')
                </p>
            </div>

            <button
                type="button"
                id="closeMobileSidebar"
                class="ml-3 flex h-10 w-10 shrink-0 items-center justify-center rounded-lg text-gray-300 transition hover:bg-gray-800 hover:text-white focus:outline-none focus:ring-2 focus:ring-red-500"
                aria-label="Close navigation menu"
            >
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>

        </div>

        {{-- MOBILE NAVIGATION --}}
        <nav
            id="mobileNavigation"
            class="flex-1 space-y-2 overflow-y-auto p-4 text-sm"
        >
            @yield('sidebar')
        </nav>

        {{-- MOBILE USER / LOGOUT --}}
        <div class="shrink-0 border-t border-gray-800 p-4">

            <p class="truncate text-sm text-gray-300">
                {{ auth()->user()->name }}
            </p>

            <form method="POST" action="/logout" class="mt-3">
                @csrf

                <button
                    type="submit"
                    class="w-full rounded-lg bg-red-600 px-4 py-3 text-sm font-medium text-white transition hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-400"
                >
                    <i class="fa-solid fa-right-from-bracket mr-2"></i>
                    Logout
                </button>
            </form>

        </div>

    </aside>


    {{-- =========================================================
         MAIN APPLICATION AREA
    ========================================================== --}}
    <div class="flex min-w-0 flex-1 flex-col overflow-hidden">

        {{-- =====================================================
             TOP HEADER
        ====================================================== --}}
        <header
            class="relative z-30 flex shrink-0 items-center justify-between gap-3 border-b border-gray-200 bg-white px-3 py-3 sm:px-4 md:px-6 md:py-4"
        >

            {{-- LEFT: MOBILE MENU + PAGE TITLE --}}
            <div class="flex min-w-0 flex-1 items-center gap-3">

                <button
                    type="button"
                    id="openMobileSidebar"
                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-700 transition hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-red-500 md:hidden"
                    aria-label="Open navigation menu"
                    aria-controls="mobileSidebar"
                    aria-expanded="false"
                >
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>

                <div class="min-w-0">
                    <h2
                        class="truncate text-sm font-semibold text-gray-800 sm:text-base md:text-lg"
                    >
                        @yield('page-title', 'Dashboard')
                    </h2>

                    {{-- Date on mobile --}}
                    <p class="mt-0.5 text-[11px] text-gray-500 sm:text-xs md:hidden">
                        {{ now()->timezone(auth()->user()->timezone ?? config('app.timezone'))->format('D, d M Y') }}
                    </p>
                </div>

            </div>


            {{-- CENTER: DATE + TIME (DESKTOP ONLY) --}}
            <div class="hidden shrink-0 flex-col text-center text-xs text-gray-600 md:flex">

                <span class="font-medium text-gray-800">
                    {{ now()->timezone(auth()->user()->timezone ?? config('app.timezone'))->format('l, d F Y') }}
                </span>

                <span class="mt-0.5 text-gray-500">
                    {{ now()->timezone(auth()->user()->timezone ?? config('app.timezone'))->format('h:i A') }}
                </span>

            </div>


            {{-- RIGHT: USER MENU --}}
            <div class="relative shrink-0" id="userMenuWrapper">

                <button
                    type="button"
                    id="userMenuBtn"
                    class="flex min-h-10 max-w-[145px] items-center gap-2 rounded-full bg-black px-3 py-2 text-xs text-white transition hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-red-500 sm:max-w-[200px] sm:text-sm"
                    aria-haspopup="true"
                    aria-controls="userDropdown"
                    aria-expanded="false"
                >

                    <span class="truncate">
                        {{ auth()->user()->name }}
                    </span>

                    <svg
                        class="h-4 w-4 shrink-0"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        aria-hidden="true"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M19 9l-7 7-7-7"
                        />
                    </svg>

                </button>


                {{-- USER DROPDOWN --}}
                <div
                    id="userDropdown"
                    class="absolute right-0 top-full z-50 mt-2 hidden w-52 max-w-[calc(100vw-1.5rem)] overflow-hidden rounded-xl border border-gray-200 bg-white py-1 text-gray-700 shadow-xl"
                >

                    <a
                        href="/profile"
                        class="block px-4 py-3 text-sm transition hover:bg-gray-100"
                    >
                        <i class="fa-regular fa-user mr-2 w-4"></i>
                        Profile
                    </a>

                    <!-- <a
                        href="/settings"
                        class="block px-4 py-3 text-sm transition hover:bg-gray-100"
                    >
                        <i class="fa-solid fa-gear mr-2 w-4"></i>
                        Settings
                    </a> -->

                    <div class="border-t border-gray-100"></div>

                    <form method="POST" action="/logout">
                        @csrf

                        <button
                            type="submit"
                            class="w-full px-4 py-3 text-left text-sm text-red-600 transition hover:bg-red-50"
                        >
                            <i class="fa-solid fa-right-from-bracket mr-2 w-4"></i>
                            Logout
                        </button>
                    </form>

                </div>

            </div>

        </header>


        {{-- =====================================================
             PAGE CONTENT
        ====================================================== --}}
        <main
            id="mainContent"
            class="min-h-0 min-w-0 flex-1 overflow-x-hidden overflow-y-auto p-3 sm:p-4 md:p-6"
        >
            @yield('content')
        </main>

    </div>

</div>


{{-- =============================================================
     TOAST NOTIFICATIONS
============================================================= --}}
<div
    id="toastContainer"
    class="pointer-events-none fixed right-3 top-3 z-[9999] flex w-auto max-w-[calc(100vw-1.5rem)] flex-col items-end gap-3 sm:right-5 sm:top-5"
    aria-live="polite"
    aria-atomic="false"
></div>


{{-- =============================================================
     JAVASCRIPT
============================================================= --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Mobile Navigation
    |--------------------------------------------------------------------------
    */

    const openMobileSidebarBtn = document.getElementById('openMobileSidebar');
    const closeMobileSidebarBtn = document.getElementById('closeMobileSidebar');
    const mobileSidebar = document.getElementById('mobileSidebar');
    const mobileSidebarOverlay = document.getElementById('mobileSidebarOverlay');
    const mobileNavigation = document.getElementById('mobileNavigation');

    let previouslyFocusedElement = null;

    function openMobileSidebar() {
        if (!mobileSidebar || !mobileSidebarOverlay) {
            return;
        }

        previouslyFocusedElement = document.activeElement;

        mobileSidebar.classList.remove('-translate-x-full');
        mobileSidebar.classList.add('translate-x-0');

        mobileSidebarOverlay.classList.remove('hidden');

        mobileSidebar.setAttribute('aria-hidden', 'false');
        mobileSidebar.removeAttribute('inert');

        openMobileSidebarBtn?.setAttribute('aria-expanded', 'true');

        document.body.style.overflow = 'hidden';

        closeMobileSidebarBtn?.focus();
    }

    function closeMobileSidebar() {
        if (!mobileSidebar || !mobileSidebarOverlay) {
            return;
        }

        mobileSidebar.classList.add('-translate-x-full');
        mobileSidebar.classList.remove('translate-x-0');

        mobileSidebarOverlay.classList.add('hidden');

        mobileSidebar.setAttribute('aria-hidden', 'true');
        mobileSidebar.setAttribute('inert', '');

        openMobileSidebarBtn?.setAttribute('aria-expanded', 'false');

        document.body.style.overflow = '';

        if (
            previouslyFocusedElement &&
            typeof previouslyFocusedElement.focus === 'function'
        ) {
            previouslyFocusedElement.focus();
        }
    }

    openMobileSidebarBtn?.addEventListener('click', openMobileSidebar);

    closeMobileSidebarBtn?.addEventListener('click', closeMobileSidebar);

    mobileSidebarOverlay?.addEventListener('click', closeMobileSidebar);

    /*
     * Close the drawer after a navigation link is selected.
     */
    mobileNavigation?.addEventListener('click', function (event) {
        const link = event.target.closest('a');

        if (link) {
            closeMobileSidebar();
        }
    });

    /*
     * Close the drawer with Escape.
     */
    document.addEventListener('keydown', function (event) {
        if (
            event.key === 'Escape' &&
            mobileSidebar &&
            !mobileSidebar.classList.contains('-translate-x-full')
        ) {
            closeMobileSidebar();
        }
    });

    /*
     * Close the mobile drawer when switching to desktop width.
     */
    window.addEventListener('resize', function () {
        if (window.innerWidth >= 768) {
            closeMobileSidebar();
        }
    });


    /*
    |--------------------------------------------------------------------------
    | User Dropdown
    |--------------------------------------------------------------------------
    */

    const userMenuBtn = document.getElementById('userMenuBtn');
    const userDropdown = document.getElementById('userDropdown');
    const userMenuWrapper = document.getElementById('userMenuWrapper');

    function closeUserDropdown() {
        userDropdown?.classList.add('hidden');
        userMenuBtn?.setAttribute('aria-expanded', 'false');
    }

    userMenuBtn?.addEventListener('click', function (event) {
        event.stopPropagation();

        const isCurrentlyHidden = userDropdown?.classList.contains('hidden');

        closeUserDropdown();

        if (isCurrentlyHidden) {
            userDropdown?.classList.remove('hidden');
            userMenuBtn.setAttribute('aria-expanded', 'true');
        }
    });

    document.addEventListener('click', function (event) {
        if (
            userMenuWrapper &&
            !userMenuWrapper.contains(event.target)
        ) {
            closeUserDropdown();
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeUserDropdown();
        }
    });


    /*
    |--------------------------------------------------------------------------
    | Preserve Existing Browser History Behaviour
    |--------------------------------------------------------------------------
    */

    if (window.history.replaceState) {
        window.history.replaceState(
            null,
            null,
            window.location.href
        );
    }

});


/*
|--------------------------------------------------------------------------
| Toast Notifications
|--------------------------------------------------------------------------
*/

function showToast(message, type = 'success') {

    const container = document.getElementById('toastContainer');

    if (!container) {
        return;
    }

    const colors = {
        success: 'bg-green-600',
        error: 'bg-red-600',
        warning: 'bg-yellow-600',
        info: 'bg-blue-600'
    };

    const icons = {
        success: 'fa-circle-check',
        error: 'fa-circle-xmark',
        warning: 'fa-triangle-exclamation',
        info: 'fa-circle-info'
    };

    const toast = document.createElement('div');

    toast.className = `
        pointer-events-auto
        flex w-full max-w-sm items-start gap-3
        rounded-xl px-4 py-3 text-white shadow-lg
        ${colors[type] ?? colors.success}
        animate-fade-in
    `;

    const icon = document.createElement('i');

    icon.className = `fa-solid ${icons[type] ?? icons.info} mt-0.5 shrink-0`;

    const text = document.createElement('span');

    text.className = 'min-w-0 break-words text-sm font-medium';

    /*
     * Use textContent so messages are displayed as text,
     * rather than interpreting message content as HTML.
     */
    text.textContent = String(message ?? '');

    toast.appendChild(icon);
    toast.appendChild(text);

    container.appendChild(toast);

    setTimeout(function () {

        toast.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-6px)';

        setTimeout(function () {
            toast.remove();
        }, 350);

    }, 3000);
}
</script>

</body>
</html>
