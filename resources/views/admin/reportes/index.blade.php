@extends('layouts.app')

@section('content')
<div class="container mx-auto space-y-6">
    <div class="rounded-2xl bg-white/90 p-6 shadow-xl shadow-emerald-200/40">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">Reportes de venta por ítem</h1>
                <p class="text-sm text-slate-500">
                    Período: {{ $periodLabel }}
                    · Ítem: {{ $selectedItemLabel }}
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('reportes.ventas.items.pdf', ['start_date' => $startDate, 'end_date' => $endDate, 'item_id' => $selectedItemId]) }}"
                    target="_blank"
                    class="inline-flex items-center gap-1 rounded-full border border-emerald-500 bg-emerald-500/10 px-4 py-2 text-sm font-semibold text-emerald-700 transition hover:bg-emerald-500/20">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                        <path d="M12 5v14" />
                        <path d="M5 12l7 7 7-7" />
                    </svg>
                    Descargar PDF
                </a>
            </div>
        </div>

        <form method="GET" action="{{ route('reportes.index') }}" class="mt-6 grid gap-4 md:grid-cols-4 md:items-end">
            <label class="flex flex-col text-sm font-medium text-slate-700">
                Fecha inicio
                <input type="date"
                    name="start_date"
                    value="{{ $startDate }}"
                    max="{{ now()->toDateString() }}"
                    class="mt-1 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
            </label>
            <label class="flex flex-col text-sm font-medium text-slate-700">
                Fecha final
                <input type="date"
                    name="end_date"
                    value="{{ $endDate }}"
                    max="{{ now()->toDateString() }}"
                    class="mt-1 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
            </label>
            <label class="flex flex-col text-sm font-medium text-slate-700">
                Ítem
                <select name="item_id"
                    class="mt-1 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
                    <option value="">Todos</option>
                    @foreach($itemOptions as $item)
                        <option value="{{ $item->id }}" {{ $item->id === $selectedItemId ? 'selected' : '' }}>
                            {{ $item->name }}
                        </option>
                    @endforeach
                </select>
            </label>
            <div class="flex flex-col text-sm font-medium text-slate-700">
                <button type="submit"
                    class="inline-flex items-center justify-center rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500">
                    Aplicar rango
                </button>
            </div>
        </form>
    </div>

        <div class="rounded-2xl bg-white/90 p-6 shadow-xl shadow-emerald-200/40">
        <h2 class="text-lg font-semibold text-slate-900">Resumen del período</h2>
        <div class="mt-4 grid gap-4 md:grid-cols-3">
            <div class="rounded-2xl border border-slate-100 bg-slate-50/60 p-4">
                <p class="text-xs uppercase tracking-wide text-slate-500">Cantidad total vendida</p>
                <p class="mt-2 text-2xl font-semibold text-slate-900">{{ number_format($totalQuantity, 0, ',', '.') }}</p>
            </div>
            <div class="rounded-2xl border border-slate-100 bg-slate-50/60 p-4">
                <p class="text-xs uppercase tracking-wide text-slate-500">Ingresos netos (después de descuentos)</p>
                <p class="mt-2 text-2xl font-semibold text-slate-900">Bs {{ number_format($totalIncome, 2, ',', '.') }}</p>
            </div>
            <div class="rounded-2xl border border-slate-100 bg-slate-50/60 p-4">
                <p class="text-xs uppercase tracking-wide text-slate-500">Ingresos del ítem (antes de descuento)</p>
                <p class="mt-2 text-2xl font-semibold text-slate-900">Bs {{ number_format($itemRevenue, 2, ',', '.') }}</p>
            </div>
        </div>
        <p class="mt-2 text-xs text-slate-500">Los descuentos no suman al ingreso neto; si no ingresó dinero, el total mostrará Bs 0,00.</p>
    </div>

    <div class="rounded-2xl bg-white/90 p-6 shadow-xl shadow-emerald-200/40">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-slate-900">Detalle por ítem</h2>
            <span class="text-sm text-slate-500">{{ $reportItems->count() }} ítems</span>
        </div>
        <div class="mt-4 overflow-x-auto">
            <table class="min-w-full border-separate border-spacing-0 text-sm text-slate-800">
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-3 py-3">Ítem</th>
                        <th class="px-3 py-3 text-right">Cantidad</th>
                        <th class="px-3 py-3 text-right">Precio promedio</th>
                        <th class="px-3 py-3 text-right">Ingreso</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reportItems as $item)
                        <tr class="border-b border-slate-100 hover:bg-slate-50">
                            <td class="px-3 py-3 font-semibold text-slate-900">{{ $item->name }}</td>
                            <td class="px-3 py-3 text-right">{{ number_format($item->quantity, 0, ',', '.') }}</td>
                            <td class="px-3 py-3 text-right">Bs {{ number_format($item->avg_price ?? 0, 2, ',', '.') }}</td>
                            <td class="px-3 py-3 text-right">Bs {{ number_format($item->revenue, 2, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-3 py-6 text-center text-sm text-slate-500">
                                No hay registros para el rango seleccionado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="rounded-2xl bg-white/90 p-6 shadow-xl shadow-emerald-200/40">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-slate-900">Ventas con promociones</h2>
            <span class="text-sm text-slate-500">{{ $salesWithPromotions->count() }} registros</span>
        </div>
        <div class="mt-4 overflow-x-auto">
            <table class="min-w-full border-separate border-spacing-0 text-sm text-slate-800">
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-3 py-3">Factura</th>
                        <th class="px-3 py-3">Fecha</th>
                        <th class="px-3 py-3">Promoción</th>
                        <th class="px-3 py-3 text-right">Tipo</th>
                        <th class="px-3 py-3 text-right">Descuento</th>
                        <th class="px-3 py-3 text-right">Total neto</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($salesWithPromotions as $sale)
                        <tr class="border-b border-slate-100 hover:bg-slate-50">
                            <td class="px-3 py-3 font-semibold text-slate-900">{{ $sale->formatted_invoice_number }}</td>
                            <td class="px-3 py-3">{{ optional($sale->sale_date)->format('d/m/Y H:i') }}</td>
                            <td class="px-3 py-3">{{ $sale->promotion?->name ?? '—' }}</td>
                            <td class="px-3 py-3 text-right">{{ $sale->promotion?->discount_type === 'percentage' ? 'Porcentaje' : 'Monto fijo' }}</td>
                            <td class="px-3 py-3 text-right">Bs {{ number_format($sale->discount_amount, 2, ',', '.') }}</td>
                            <td class="px-3 py-3 text-right">Bs {{ number_format($sale->total, 2, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-3 py-6 text-center text-sm text-slate-500">
                                No se registraron promociones en este rango.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
