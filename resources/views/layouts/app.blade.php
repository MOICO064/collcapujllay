<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Collcapujllay') }}</title>
    <link rel="icon" href="{{ asset('img/logo.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        slate: {
                            50: '#f5fcf9',
                            100: '#eef8f4',
                            200: '#d8ede4',
                            300: '#b8e4cf',
                            900: '#0b1c17',
                        },
                        mint: '#1dd6af',
                        forest: '#0a3a2e',
                        jade: '#1b8b74',
                    },
                },
            },
        };
    </script>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    @stack('head')
</head>

<body class="bg-gradient-to-b from-slate-50 via-mint/5 to-slate-100 text-slate-900 min-h-screen overflow-hidden">

    <div class="flex h-screen w-full">

        <!-- Sidebar -->
        <x-sidebar class="flex-shrink-0 w-full md:w-64" />

        <!-- Contenido principal -->
        <div class="flex flex-1 flex-col min-w-0 overflow-hidden">
            <x-navbar />
            <main class="flex-1 min-h-0 overflow-y-auto p-4 md:p-6 bg-white/80 shadow-inner shadow-emerald-200/30">
                @yield('content')
                {{ $slot ?? '' }}
            </main>
        </div>
    </div>

    <script defer src="{{ asset('js/layout.js') }}"></script>
    @stack('scripts')
</body>

</html>
