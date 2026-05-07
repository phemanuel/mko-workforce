<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MKO Workforce')</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="{{asset('login/favicon.png')}}" rel="shortcut icon" type="image/png">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background: #f5f5f5;
        }

        .brand-black { background: #0f0f0f; }
        .brand-red { background: #dc2626; }
        .text-brand-red { color: #dc2626; }
        .border-brand-red { border-color: #dc2626; }
    </style>

    @stack('styles')
</head>
<header class="bg-white border-b px-4 md:px-6 py-4 flex justify-between items-center">

    <!-- MOBILE MENU BUTTON -->
    <button class="md:hidden text-black text-2xl">
        ☰
    </button>

    <h2 class="font-semibold text-gray-700 text-sm md:text-base">
        @yield('page-title')
    </h2>

    

</header>
<body class="min-h-screen text-gray-800">

    @yield('content')

    @stack('scripts')

</body>
</html>
<script>
function togglePassword(fieldId, btn) {
    const input = document.getElementById(fieldId);

    if (!input) return;

    if (input.type === "password") {
        input.type = "text";

        btn.innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 012.243-3.592M6.228 6.228A9.956 9.956 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.97 9.97 0 01-4.132 5.411M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>`;
    } else {
        input.type = "password";

        btn.innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7
                -1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
        </svg>`;
    }
}
</script>