<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="font-sans text-gray-900 antialiased">
    @php $isLogin = request()->routeIs('login'); @endphp
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 {{ $isLogin ? 'bg-cover bg-center bg-no-repeat' : 'bg-gray-100' }}"
        @if($isLogin) style="background-image:url('{{ asset('img/fondo.jpeg') }}');" @endif>
        <div class="mb-6">
            <a href="/">
                @if($isLogin)
                <img src="{{ asset('img/logo.png') }}" alt="Logo Collcapujllay"
                    class="w-28 h-28 rounded-full border-4 border-amber-300 shadow-2xl" />
                @else
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                @endif
            </a>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 {{ $isLogin ? 'bg-black/70 border border-amber-300 text-amber-100 shadow-2xl sm:rounded-3xl' : 'bg-white shadow-md overflow-hidden sm:rounded-lg' }}">
            {{ $slot }}
        </div>
    </div>
</body>

</html>