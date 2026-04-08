@extends('layouts.app')

@section('content')
@php
    $summary = $summary ?? [
        'sales_today_count' => 0,
        'sales_today_revenue' => 0,
        'monthly_revenue' => 0,
        'monthly_sales_count' => 0,
        'average_ticket' => 0,
    ];
    $salesTrend = $salesTrend ?? collect();
    $trendMax = max($salesTrend->pluck('amount')->max() ?? 0, 1);
    $activePromotions = $activePromotions ?? 0;
    $usersCount = $usersCount ?? 0;
    $totalSales = $totalSales ?? 0;
    $usageRatio = $usageRatio ?? 0;
    $recentSales = $recentSales ?? collect();
    $topItems = $topItems ?? collect();
    $promotionUsage = $promotionUsage ?? collect();
    $totalPromotionUsage = $totalPromotionUsage ?? 0;
@endphp

<div class="space-y-6">
    <div class="grid gap-4 lg:grid-cols-3">
        <div class="rounded-2xl border border-slate-100 bg-white/90 p-5 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs uppercase font-semibold tracking-wide text-emerald-600">Cajero</p>
                    <p class="text-sm text-slate-500">Turno actual</p>
                </div>
                <span class="text-xs font-semibold uppercase text-slate-400">Hoy</span>
            </div>
            <div class="mt-4 space-y-3">
                <div class="rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Ventas</p>
                    <p class="text-3xl font-semibold text-slate-900">{{ $summary['sales_today_count'] }}</p>
                </div>
                <div class="rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Ingreso</p>
                    <p class="text-2xl font-semibold text-emerald-600">Bs {{ number_format($summary['sales_today_revenue'], 2, ',', '.') }}</p>
                </div>
                <div class="rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Ticket promedio</p>
                    <p class="text-2xl font-semibold text-slate-900">Bs {{ number_format($summary['average_ticket'], 2, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="rounded-2xl border border-slate-100 bg-white/90 p-5 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs uppercase font-semibold tracking-wide text-slate-600">Administrador</p>
                    <p class="text-sm text-slate-500">Resumen mensual</p>
                </div>
                <span class="text-xs font-semibold uppercase text-slate-400">Mes actual</span>
            </div>
            <div class="mt-4 space-y-3">
                <div class="rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Ventas</p>
                    <p class="text-3xl font-semibold text-slate-900">{{ number_format($summary['monthly_sales_count'], 0, ',', '.') }}</p>
                </div>
                <div class="rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Ingreso</p>
                    <p class="text-2xl font-semibold text-slate-900">Bs {{ number_format($summary['monthly_revenue'], 2, ',', '.') }}</p>
                </div>
                <div class="rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3 flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-500">Promociones activas</p>
                        <p class="text-2xl font-semibold text-emerald-600">{{ $activePromotions }}</p>
                    </div>
                    <span class="text-xs text-slate-500">{{ $usageRatio }} % uso</span>
                </div>
            </div>
        </div>
        <div class="rounded-2xl border border-slate-100 bg-white/90 p-5 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs uppercase font-semibold tracking-wide text-slate-600">Superusuario</p>
                    <p class="text-sm text-slate-500">Control global</p>
                </div>
                <span class="text-xs font-semibold uppercase text-slate-400">Siempre activo</span>
            </div>
            <div class="mt-4 space-y-3">
                <div class="rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Ventas registradas</p>
                    <p class="text-3xl font-semibold text-emerald-700">{{ number_format($totalSales, 0, ',', '.') }}</p>
                </div>
                <div class="rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Usuarios</p>
                    <p class="text-2xl font-semibold text-slate-900">{{ number_format($usersCount, 0, ',', '.') }}</p>
                </div>
                <div class="rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Promociones usadas</p>
                    <p class="text-2xl font-semibold text-slate-900">{{ number_format($totalPromotionUsage, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-100 bg-white/90 p-5 shadow-xl">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs uppercase font-semibold tracking-wide text-slate-500">Tendencia semanal</p>
                <p class="text-lg font-semibold text-slate-900">Ingresos diarios</p>
            </div>
            <span class="text-xs font-semibold uppercase text-slate-400">Últimos 7 días</span>
        </div>
        <div class="mt-4 space-y-2">
            @foreach($salesTrend as $point)
                <div class="flex items-center gap-3">
                    <span class="w-12 text-xs text-slate-500">{{ $point['label'] }}</span>
                    <div class="flex-1 rounded-full bg-slate-100">
                        <div class="h-2 rounded-full bg-emerald-500" style="width: {{ min(100, ($point['amount'] / $trendMax) * 100) }}%;"></div>
                    </div>
                    <span class="w-24 text-right text-xs font-semibold text-slate-700">Bs {{ number_format($point['amount'], 2, ',', '.') }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <div class="grid gap-4 lg:grid-cols-2">
        <div class="rounded-2xl border border-slate-100 bg-white/90 p-5 shadow-lg">
            <h2 class="text-lg font-semibold text-slate-900">Top ítems</h2>
            <p class="text-sm text-slate-500">Productos que lideran los ingresos.</p>
            <div class="mt-4 space-y-3">
                @forelse($topItems as $item)
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div>
                            <p class="text-sm font-semibold text-slate-900">{{ $item->item_name }}</p>
                            <p class="text-xs text-slate-500">Cant. {{ number_format($item->quantity, 0, ',', '.') }}</p>
                        </div>
                        <span class="text-sm font-semibold text-emerald-600">Bs {{ number_format($item->revenue, 2, ',', '.') }}</span>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Aún no se registran ítems destacados.</p>
                @endforelse
            </div>
        </div>

        <div class="rounded-2xl border border-slate-100 bg-white/90 p-5 shadow-lg">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-slate-900">Promociones recientes</h2>
                <span class="text-xs font-semibold text-slate-400">Usos registrados {{ number_format($totalPromotionUsage, 0, ',', '.') }}</span>
            </div>
            <p class="text-sm text-slate-500">Últimos usos, cédulas y promociones vigentes.</p>
            <div class="mt-4 space-y-2 text-sm text-slate-600">
                @forelse($promotionUsage as $usage)
                    <div class="rounded-lg border border-slate-100 px-4 py-3">
                        <div class="flex items-center justify-between">
                            <span class="font-semibold text-slate-900">{{ $usage->promotion?->name ?? 'Promoción' }}</span>
                            <span class="text-xs text-slate-500">{{ optional($usage->used_at)->format('d/m/Y H:i') }}</span>
                        </div>
                        <p class="text-xs text-slate-500">Venta {{ $usage->sale?->formatted_invoice_number ?? '—' }} · CI {{ $usage->ci ?? '—' }}</p>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">No se han registrado usos aún.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="grid gap-4 lg:grid-cols-2">
        <div class="rounded-2xl border border-slate-100 bg-white/90 p-5 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Operaciones clave</h2>
                    <p class="text-sm text-slate-500">Seguimiento inmediato de las últimas operaciones.</p>
                </div>
                <a href="{{ route('ventas.create') }}" class="text-sm font-semibold text-emerald-600 hover:text-emerald-500">Registrar venta</a>
            </div>
            <div class="mt-4 space-y-3 text-sm text-slate-600">
                @forelse($recentSales as $sale)
                    <div class="rounded-lg border border-slate-100 px-4 py-3">
                        <div class="flex items-center justify-between">
                            <span class="font-semibold text-slate-900">#{{ $sale->formatted_invoice_number }}</span>
                            <span class="text-xs text-slate-400">Bs {{ number_format($sale->total, 2, ',', '.') }}</span>
                        </div>
                        <p class="text-xs text-slate-500">{{ optional($sale->sale_date)->format('d/m/Y H:i') }}</p>
                        <p class="text-xs text-slate-500">Promoción: {{ $sale->promotion?->name ?? 'Sin promoción' }}</p>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">No hay ventas recientes para mostrar.</p>
                @endforelse
            </div>
        </div>

        <div class="rounded-2xl border border-slate-100 bg-white/90 p-5 shadow-lg">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">Control global</h2>
                <p class="text-sm text-slate-500">Indicadores clave para supervisores.</p>
            </div>
            <div class="mt-4 grid gap-3 sm:grid-cols-2">
                <div class="rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3 text-center">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Promociones activas</p>
                    <p class="text-2xl font-semibold text-emerald-600">{{ $activePromotions }}</p>
                </div>
                <div class="rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3 text-center">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Vent. totales</p>
                    <p class="text-2xl font-semibold text-slate-900">{{ number_format($totalSales, 0, ',', '.') }}</p>
                </div>
                <div class="rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3 text-center">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Usuarios</p>
                    <p class="text-2xl font-semibold text-slate-900">{{ number_format($usersCount, 0, ',', '.') }}</p>
                </div>
                <div class="rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3 text-center">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Uso promos</p>
                    <p class="text-2xl font-semibold text-slate-900">{{ $usageRatio }}%</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
