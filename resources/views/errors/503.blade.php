<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error 503 - {{ config('app.name', 'Collcapujllay') }}</title>
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
</head>
<body class="bg-gradient-to-b from-slate-50 via-mint/5 to-slate-100 text-slate-900 min-h-screen flex items-center justify-center">
    <div class="text-center">
        <img src="{{ asset('img/logo.png') }}" alt="Logo" class="mx-auto mb-8 w-96 h-96 drop-shadow-2xl">
        <h1 class="text-8xl font-bold text-forest mb-4">503</h1>
        <p class="text-2xl mb-8 text-jade">Servicio no disponible</p>
        <a href="{{ route('dashboard') }}" class="bg-green-500 hover:bg-green-600 text-white px-8 py-4 rounded-lg text-lg font-semibold transition duration-300 shadow-lg">Inicio</a>
    </div>
</body>
</html>