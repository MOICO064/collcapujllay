<header class="sticky top-0 z-30 md:z-50 border-b border-emerald-100 bg-gradient-to-r from-white to-emerald-50/60 px-4 py-3 shadow-sm backdrop-blur-md">
    <div class="flex items-center justify-between gap-4">
        <!-- Botones y título -->
        <div class="flex items-center gap-3">
            <button id="sidebar-toggle" type="button" aria-expanded="true" aria-controls="dashboard-sidebar"
                class="hidden md:inline-flex h-11 w-11 items-center justify-center rounded-full border border-emerald-200 bg-white text-emerald-600 transition hover:border-emerald-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-300">
                <span class="sr-only">Colapsar menú</span>
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="4" y1="7" x2="20" y2="7" />
                    <line x1="4" y1="12" x2="16" y2="12" />
                    <line x1="4" y1="17" x2="14" y2="17" />
                </svg>
            </button>

            <button id="sidebar-mobile-open" type="button"
                class="md:hidden inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-white px-3 py-1 text-xs font-semibold uppercase tracking-[0.4em] text-emerald-700 transition hover:border-emerald-400">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="4" y1="7" x2="20" y2="7" />
                    <line x1="4" y1="12" x2="20" y2="12" />
                    <line x1="4" y1="17" x2="20" y2="17" />
                </svg>
                <span class="hidden md:inline">Menú</span>
            </button>

            <div class="hidden md:block">
                <p class="text-xs uppercase tracking-[0.5em] text-emerald-600">Panel administrativo</p>
                <h1 class="text-lg font-semibold text-emerald-900">Control interno</h1>
            </div>
        </div>

        <!-- Usuario -->
        <div class="flex items-center gap-3">
            <div class="flex flex-col text-xs text-emerald-600">
                <span class="text-sm font-semibold tracking-[0.3em] uppercase">{{ Auth::user()->name ?? 'Usuario' }}</span>
                <span class="text-[0.6rem] font-bold tracking-[0.5em] uppercase text-emerald-500">
                    {{ Auth::user()->roles->first()?->name ?? 'Sin rol' }}
                </span>
            </div>
            <div class="flex h-10 w-10 items-center justify-center rounded-full border border-white/20 bg-white text-emerald-700 font-semibold">
                {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
            </div>
        </div>
    </div>
</header>