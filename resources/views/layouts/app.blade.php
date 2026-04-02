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
                            950: '#020b08',
                        },
                        forest: '#0d3b2e',
                        viridian: '#1dd6af',
                        mint: '#75ffe7',
                    },
                },
            },
        };
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    @stack('head')
    <style>
        body {
            font-family: 'Figtree', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        #sidebar-mobile-panel {
            will-change: transform;
        }
    </style>
</head>

<body class="min-h-screen bg-slate-950 text-slate-100">
    <div class="flex min-h-screen">
        <x-sidebar />
        <div class="flex flex-1 flex-col">
            <x-navbar />
            <main class="flex-1 bg-slate-950 p-4 md:p-6">
                @yield('content')
                {{ $slot ?? '' }}
            </main>
        </div>
    </div>

    <script>
        (() => {
            const desktopSidebar = document.getElementById('dashboard-sidebar');
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const storageKey = 'collcap-dashboard-sidebar';

            const labels = desktopSidebar ? desktopSidebar.querySelectorAll('[data-sidebar-label]') : [];
            const setDesktopState = (expanded) => {
                if (!desktopSidebar) {
                    return;
                }
                desktopSidebar.classList.toggle('w-64', expanded);
                desktopSidebar.classList.toggle('w-20', !expanded);
                labels.forEach((label) => {
                    label.classList.toggle('opacity-0', !expanded);
                    label.classList.toggle('pointer-events-none', !expanded);
                    label.classList.toggle('hidden', !expanded);
                });
                if (sidebarToggle) {
                    sidebarToggle.setAttribute('aria-expanded', expanded);
                }
                localStorage.setItem(storageKey, expanded);
            };

            const stored = localStorage.getItem(storageKey);
            const shouldExpand = stored === null ? true : stored === 'true';
            setDesktopState(shouldExpand);

            sidebarToggle?.addEventListener('click', () => {
                const currentlyExpanded = desktopSidebar?.classList.contains('w-64');
                setDesktopState(!currentlyExpanded);
            });

            const mobilePanel = document.getElementById('sidebar-mobile-panel');
            const mobileBackdrop = document.getElementById('sidebar-mobile-backdrop');
            const mobileOpen = document.getElementById('sidebar-mobile-open');
            const mobileClose = document.getElementById('sidebar-mobile-close');

            const setMobileOpen = (open) => {
                if (!mobilePanel || !mobileBackdrop) {
                    return;
                }
                mobilePanel.classList.toggle('-translate-x-full', !open);
                mobileBackdrop.classList.toggle('opacity-0', !open);
                mobileBackdrop.classList.toggle('pointer-events-none', !open);
            };

            mobileOpen?.addEventListener('click', () => setMobileOpen(true));
            mobileClose?.addEventListener('click', () => setMobileOpen(false));
            mobileBackdrop?.addEventListener('click', () => setMobileOpen(false));
        })();
    </script>

    @stack('scripts')
</body>

</html>
