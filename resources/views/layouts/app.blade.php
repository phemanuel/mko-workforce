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