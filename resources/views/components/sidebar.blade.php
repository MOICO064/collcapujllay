<aside id="dashboard-sidebar" class="hidden md:flex md:sticky md:top-0 md:self-start md:h-screen flex-col border border-emerald-200 bg-gradient-to-b from-emerald-50/80 via-slate-50 to-white text-slate-900 shadow-xl transition-all duration-300 w-64 backdrop-blur-2xl">
    <div class="flex items-center justify-center border-b border-emerald-200 px-5 py-8 md:px-1 md:py-2">
        <img data-sidebar-logo src="{{ asset('img/logo.png') }}" alt="Logo Parque Collcapujllay" class="h-28 w-28 object-contain transition-all duration-200" />
    </div>

    <nav class="flex flex-col gap-1 px-3 py-5 overflow-y-auto">
        <!-- Tablero -->
        <a href="{{ route('dashboard') }}" title="Tablero"
            class="group flex items-center gap-3 rounded-2xl px-3 py-3 text-base font-semibold transition
           {{ request()->routeIs('dashboard') ? 'bg-emerald-50 text-emerald-600' : 'text-slate-900 hover:bg-emerald-50' }}">
            <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-white shadow-[0_6px_20px_rgba(17,101,84,0.15)] transition
                {{ request()->routeIs('dashboard') ? 'text-emerald-700' : 'text-emerald-600 group-hover:bg-emerald-50' }}">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                    <path d="M3 12l9-9 9 9" />
                    <path d="M5 11v10h14V11" />
                </svg>
            </span>
            <span data-sidebar-label class="transition-all duration-200">Tablero</span>
        </a>
        <!-- Usuarios -->
        <a href="{{ route('usuarios.index') }}" title="Usuarios" class="group flex items-center gap-3 rounded-2xl px-3 py-3 text-base font-semibold transition
    {{ request()->routeIs('usuarios.*') ? 'bg-emerald-50 text-emerald-600' : 'text-slate-900 hover:bg-emerald-50' }}">

            <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-white shadow-[0_6px_20px_rgba(17,101,84,0.15)] transition
        {{ request()->routeIs('usuarios.*') ? 'text-emerald-700' : 'text-emerald-600 group-hover:bg-emerald-50' }}">

                <!-- icon usuarios -->
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                    <path d="M17 21v-2a4 4 0 0 0-3-3.87" />
                    <path d="M7 21v-2a4 4 0 0 1 3-3.87" />
                    <circle cx="12" cy="7" r="4" />
                </svg>
            </span>

            <span data-sidebar-label class="transition-all duration-200">Usuarios</span>
        </a>
        <!-- Roles -->
        <a href="{{ route('roles.index') }}" title="Roles" class="group flex items-center gap-3 rounded-2xl px-3 py-3 text-base font-semibold transition
    {{ request()->routeIs('roles.*') ? 'bg-emerald-50 text-emerald-600' : 'text-slate-900 hover:bg-emerald-50' }}">

            <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-white shadow-[0_6px_20px_rgba(17,101,84,0.15)] transition
        {{ request()->routeIs('roles.*') ? 'text-emerald-700' : 'text-emerald-600 group-hover:bg-emerald-50' }}">

                <!-- icon roles/seguridad -->
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                    <path d="M12 3l7 4v6c0 5-3.5 8-7 8s-7-3-7-8V7l7-4z" />
                </svg>
            </span>

            <span data-sidebar-label class="transition-all duration-200">Roles</span>
        </a>

        <!-- Permisos -->
        <a href="{{ route('permisos.index') }}" title="Permisos" class="group flex items-center gap-3 rounded-2xl px-3 py-3 text-base font-semibold transition
    {{ request()->routeIs('permisos.*') ? 'bg-emerald-50 text-emerald-600' : 'text-slate-900 hover:bg-emerald-50' }}">

            <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-white shadow-[0_6px_20px_rgba(17,101,84,0.15)] transition
        {{ request()->routeIs('permisos.*') ? 'text-emerald-700' : 'text-emerald-600 group-hover:bg-emerald-50' }}">

                <!-- icon permisos -->
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                    <path d="M5 12h14M12 5v14" />
                </svg>
            </span>

            <span data-sidebar-label class="transition-all duration-200">Permisos</span>
        </a>
        <!-- Categorías -->
        <a href="{{ route('categorias.index') }}" title="Categorías" class="group flex items-center gap-3 rounded-2xl px-3 py-3 text-base font-semibold transition
    {{ request()->routeIs('categorias.*') ? 'bg-emerald-50 text-emerald-600' : 'text-slate-900 hover:bg-emerald-50' }}">

            <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-white shadow-[0_6px_20px_rgba(17,101,84,0.15)] transition
        {{ request()->routeIs('categorias.*') ? 'text-emerald-700' : 'text-emerald-600 group-hover:bg-emerald-50' }}">

                <!-- icon categorías -->
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                    <path d="M4 6h16M4 12h16M4 18h16" /> <!-- ícono de lista / categorías -->
                </svg>
            </span>

            <span data-sidebar-label class="transition-all duration-200">Categorías</span>
        </a>
        <!-- Items -->
        <a href="{{ route('items.index') }}" title="Items" class="group flex items-center gap-3 rounded-2xl px-3 py-3 text-base font-semibold transition
{{ request()->routeIs('items.*') ? 'bg-emerald-50 text-emerald-600' : 'text-slate-900 hover:bg-emerald-50' }}">

            <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-white shadow-[0_6px_20px_rgba(17,101,84,0.15)] transition
    {{ request()->routeIs('items.*') ? 'text-emerald-700' : 'text-emerald-600 group-hover:bg-emerald-50' }}">

                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                    <path d="M4 7h16v12H4z" />
                    <path d="M4 5h16v2H4z" />
                    <path d="M9 11h6" />
                </svg>
            </span>

            <span data-sidebar-label class="transition-all duration-200">Items</span>
        </a>
        <!-- Promociones -->
        <a href="{{ route('promociones.index') }}" title="Promociones" class="group flex items-center gap-3 rounded-2xl px-3 py-3 text-base font-semibold transition
{{ request()->routeIs('promociones.*') ? 'bg-emerald-50 text-emerald-600' : 'text-slate-900 hover:bg-emerald-50' }}">

            <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-white shadow-[0_6px_20px_rgba(17,101,84,0.15)] transition
    {{ request()->routeIs('promociones.*') ? 'text-emerald-700' : 'text-emerald-600 group-hover:bg-emerald-50' }}">

                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                    <path d="M21 10V5a2 2 0 0 0-2-2h-5L3 12l7 7h5a2 2 0 0 0 2-2v-5z" />
                    <circle cx="16.5" cy="7.5" r="1.5" />
                </svg>
            </span>

            <span data-sidebar-label class="transition-all duration-200">Promociones</span>
        </a>

        <!-- Ventas -->
        <a href="{{ route('ventas.index') }}" title="Ventas" class="group flex items-center gap-3 rounded-2xl px-3 py-3 text-base font-semibold transition
{{ request()->routeIs('ventas.*') ? 'bg-emerald-50 text-emerald-600' : 'text-slate-900 hover:bg-emerald-50' }}">

            <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-white shadow-[0_6px_20px_rgba(17,101,84,0.15)] transition
{{ request()->routeIs('ventas.*') ? 'text-emerald-700' : 'text-emerald-600 group-hover:bg-emerald-50' }}">

                <!-- Icono tipo carrito / venta -->
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                    <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.5 7h13M10 21a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm8 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2z" />
                </svg>
            </span>

            <span data-sidebar-label class="transition-all duration-200">Ventas</span>
        </a>


        <!-- Reportes -->
        <a href="{{ route('reportes.index') }}" title="Reportes"
            class="group flex items-center gap-3 rounded-2xl px-3 py-3 text-base font-semibold transition
           {{ request()->routeIs('reportes.*') ? 'bg-emerald-50 text-emerald-600' : 'text-slate-900 hover:bg-emerald-50' }}">
            <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-white shadow-[0_6px_20px_rgba(17,101,84,0.15)] transition
                {{ request()->routeIs('reportes.*') ? 'text-emerald-700' : 'text-emerald-600 group-hover:bg-emerald-50' }}">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                    <path d="M4 19h16V5H4v14z" />
                    <path d="M9 9h6" />
                    <path d="M9 13h6" />
                </svg>
            </span>
            <span data-sidebar-label class="transition-all duration-200">Reportes</span>
        </a>



    </nav>

    <div class="mt-auto border-t border-emerald-100 px-4 py-4">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="mt-2 flex w-full items-center justify-center gap-2 rounded-2xl border border-emerald-200 bg-emerald-600/95 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-500">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="M16 17l5-5-5-5" />
                    <path d="M21 12H9" />
                    <path d="M8 5H6a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h2" />
                </svg>
                <span data-sidebar-logout-label class="transition-all duration-200">Cerrar sesión</span>
            </button>
        </form>
    </div>
</aside>

<div id="sidebar-mobile-panel" class="md:hidden fixed inset-y-0 left-0 z-50 ml-0 flex w-64 -translate-x-full flex-col border border-emerald-200 bg-gradient-to-b from-emerald-50/90 via-white to-white text-slate-900 shadow-2xl transition-transform duration-300 ease-in-out backdrop-blur-xl">
    <div class="flex items-center justify-between border-b border-emerald-100 px-4 py-4">
        <img src="{{ asset('img/logo.png') }}" alt="Logo Parque Collcapujllay" class="h-12 w-12 object-contain" />
        <button id="sidebar-mobile-close" class="rounded-full bg-white p-2 text-slate-700 transition hover:bg-emerald-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-200">
            <span class="sr-only">Cerrar menú</span>
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <line x1="18" y1="6" x2="6" y2="18" />
                <line x1="6" y1="6" x2="18" y2="18" />
            </svg>
        </button>
    </div>

    <nav class="flex flex-1 flex-col gap-1 overflow-y-auto px-3 py-5">

        <!-- Tablero -->
        <a href="{{ route('dashboard') }}"
            class="flex items-center gap-3 rounded-2xl px-3 py-3 text-base font-semibold transition
           {{ request()->routeIs('dashboard') ? 'bg-emerald-50 text-emerald-600' : 'text-slate-900 hover:bg-emerald-50' }}">
            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-white shadow-[0_6px_20px_rgba(17,101,84,0.15)]
                {{ request()->routeIs('dashboard') ? 'text-emerald-700' : 'text-emerald-600' }}">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                    <path d="M3 12l9-9 9 9" />
                    <path d="M5 11v10h14V11" />
                </svg>
            </span>
            <span>Tablero</span>
        </a>

        <!-- Operaciones -->
        <a href="#operaciones"
            class="flex items-center gap-3 rounded-2xl px-3 py-3 text-base font-semibold transition
           {{ request()->routeIs('operaciones.*') ? 'bg-emerald-50 text-emerald-600' : 'text-slate-900 hover:bg-emerald-50' }}">
            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-white shadow-[0_6px_20px_rgba(17,101,84,0.15)]
                {{ request()->routeIs('operaciones.*') ? 'text-emerald-700' : 'text-emerald-600' }}">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                    <path d="M6 18V8" />
                    <path d="M10 18V12" />
                    <path d="M14 18v-4" />
                    <path d="M18 18V6" />
                    <path d="M3 21h18" />
                </svg>
            </span>
            <span>Operaciones</span>
        </a>

        <!-- Entradas -->
        <a href="#entradas"
            class="flex items-center gap-3 rounded-2xl px-3 py-3 text-base font-semibold transition
           {{ request()->routeIs('entradas.*') ? 'bg-emerald-50 text-emerald-600' : 'text-slate-900 hover:bg-emerald-50' }}">
            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-white shadow-[0_6px_20px_rgba(17,101,84,0.15)]
                {{ request()->routeIs('entradas.*') ? 'text-emerald-700' : 'text-emerald-600' }}">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                    <path d="M3 7h18" />
                    <path d="M3 17h18" />
                    <path d="M12 7v10" />
                    <path d="M6 3h12v4" />
                    <path d="M6 17v4h12v-4" />
                </svg>
            </span>
            <span>Entradas</span>
        </a>

        <!-- Reportes -->
        <a href="{{ route('reportes.index') }}"
            class="flex items-center gap-3 rounded-2xl px-3 py-3 text-base font-semibold transition
           {{ request()->routeIs('reportes.*') ? 'bg-emerald-50 text-emerald-600' : 'text-slate-900 hover:bg-emerald-50' }}">
            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-white shadow-[0_6px_20px_rgba(17,101,84,0.15)]
                {{ request()->routeIs('reportes.*') ? 'text-emerald-700' : 'text-emerald-600' }}">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                    <path d="M4 19h16V5H4v14z" />
                    <path d="M9 9h6" />
                    <path d="M9 13h6" />
                </svg>
            </span>
            <span>Reportes</span>
        </a>

        <!-- Configuración -->
        <a href="#configuracion"
            class="flex items-center gap-3 rounded-2xl px-3 py-3 text-base font-semibold transition
           {{ request()->routeIs('configuracion.*') ? 'bg-emerald-50 text-emerald-600' : 'text-slate-900 hover:bg-emerald-50' }}">
            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-white shadow-[0_6px_20px_rgba(17,101,84,0.15)]
                {{ request()->routeIs('configuracion.*') ? 'text-emerald-700' : 'text-emerald-600' }}">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="12" cy="12" r="3" />
                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 1 1-4 0v-.09a1.65 1.65 0 0 0-1-1.51 1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09a1.65 1.65 0 0 0 1.51-1 1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z" />
                </svg>
            </span>
            <span>Configuración</span>
        </a>

    </nav>

    <div class="mt-auto border-t border-emerald-100 px-4 py-4">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="mt-2 flex w-full items-center justify-center rounded-2xl border border-emerald-200 bg-emerald-600/95 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-500">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="M16 17l5-5-5-5" />
                    <path d="M21 12H9" />
                    <path d="M8 5H6a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h2" />
                </svg>
                <span class="sr-only">Cerrar sesión</span>
            </button>
        </form>
    </div>
</div>

<div id="sidebar-mobile-backdrop" class="md:hidden pointer-events-none fixed inset-0 z-40 bg-slate-950/80 opacity-0 transition-opacity duration-300"></div>
