<?php

return [
    'nav_items' => [
        [
            'label' => 'Tablero',
            'route' => 'dashboard',
            'icon' => 'home',
            'match' => 'dashboard*',
        ],
        [
            'label' => 'Operaciones',
            'href' => '#operaciones',
            'icon' => 'chart',
        ],
        [
            'label' => 'Entradas',
            'href' => '#entradas',
            'icon' => 'ticket',
        ],
        [
            'label' => 'Reportes',
            'href' => '#reportes',
            'icon' => 'report',
        ],
        [
            'label' => 'Configuración',
            'href' => '#configuracion',
            'icon' => 'settings',
        ],
    ],
    'icons' => [
        'home' => '<svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    <path d="M3 12l9-9 9 9" />
    <path d="M5 11v10h14V11" />
</svg>',
        'chart' => '<svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    <path d="M6 18V8" />
    <path d="M10 18V12" />
    <path d="M14 18v-4" />
    <path d="M18 18V6" />
    <path d="M3 21h18" />
</svg>',
        'ticket' => '<svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    <path d="M3 7h18" />
    <path d="M3 17h18" />
    <path d="M12 7v10" />
    <path d="M6 3h12v4" />
    <path d="M6 17v4h12v-4" />
</svg>',
        'report' => '<svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    <path d="M4 19h16V5H4v14z" />
    <path d="M9 9h6" />
    <path d="M9 13h6" />
</svg>',
        'settings' => '<svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    <circle cx="12" cy="12" r="3" />
    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 1 1-4 0v-.09a1.65 1.65 0 0 0-1-1.51 1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09a1.65 1.65 0 0 0 1.51-1 1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z" />
</svg>',
    ],
];
