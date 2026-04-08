@extends('layouts.app')

@section('content')
<div id="report-wrapper" class="mx-auto max-w-6xl space-y-6">
    <div class="relative">
        <div id="report-loading" class="pointer-events-none absolute inset-0 z-50 flex items-center justify-center rounded-2xl bg-slate-900/50 opacity-0 hidden transition duration-200" aria-live="polite">
            <div class="rounded-xl bg-white px-6 py-4 text-sm font-semibold text-slate-900 shadow-xl">
                Cargando reporte...
            </div>
        </div>

        <div class="rounded-2xl bg-white/95 p-6 shadow-xl shadow-emerald-200/40">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-wide text-emerald-600">Reporte detallado</p>
                    <h1 class="text-2xl font-bold text-slate-900">Ventas por ítem</h1>
                    <p class="text-sm text-slate-500 mt-1">
                        <span class="font-semibold">Período:</span>
                        <span id="report-period-label">{{ $periodLabel }}</span>
                        · Ítem: <span id="report-item-label">{{ $selectedItemLabel }}</span>
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <a id="report-pdf-link" data-base-url="{{ route('reportes.ventas.items.pdf') }}"
                        href="{{ route('reportes.ventas.items.pdf', ['start_date' => $startDate, 'end_date' => $endDate, 'item_id' => $selectedItemId]) }}"
                        target="_blank"
                        class="inline-flex items-center gap-2 rounded-full border border-emerald-500 bg-emerald-500/10 px-4 py-2 text-sm font-semibold text-emerald-700 transition hover:bg-emerald-500/20">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                            <path d="M12 5v14" />
                            <path d="M5 12l7 7 7-7" />
                        </svg>
                        Descargar PDF
                    </a>
                </div>
            </div>

            <form id="report-form" method="GET" action="{{ route('reportes.index') }}" class="mt-6 grid gap-4 md:grid-cols-4 items-end">
                <label class="text-sm font-medium text-slate-700">
                    Fecha inicio
                    <input type="date"
                        name="start_date"
                        value="{{ $startDate }}"
                        max="{{ now()->toDateString() }}"
                        class="mt-1 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
                </label>
                <label class="text-sm font-medium text-slate-700">
                    Fecha final
                    <input type="date"
                        name="end_date"
                        value="{{ $endDate }}"
                        max="{{ now()->toDateString() }}"
                        class="mt-1 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
                </label>
                <label class="text-sm font-medium text-slate-700">
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
                <div class="flex items-end justify-end">
                    <button type="submit"
                        class="inline-flex w-full items-center justify-center gap-1 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold uppercase tracking-wide text-white transition hover:bg-emerald-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500 md:w-auto">
                        Aplicar rango
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="rounded-2xl bg-white/95 p-6 shadow-xl shadow-emerald-200/40">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">Resumen del período</h2>
                <p class="text-sm text-slate-500">Los valores reflejan ventas activas dentro del rango seleccionado.</p>
            </div>
            <span class="text-xs font-semibold uppercase tracking-wide text-slate-400">AJAX + PDF sincronizados</span>
        </div>
        <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-4">
                <p class="text-xs uppercase tracking-wide text-slate-500">Cantidad total</p>
                <p id="summary-total-quantity" class="mt-2 text-2xl font-semibold text-slate-900">{{ number_format($totalQuantity, 0, ',', '.') }}</p>
            </div>
            <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-4">
                <p class="text-xs uppercase tracking-wide text-slate-500">Ingresos netos</p>
                <p id="summary-total-income" class="mt-2 text-2xl font-semibold text-emerald-600">Bs {{ number_format($totalIncome, 2, ',', '.') }}</p>
            </div>
            <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-4">
                <p class="text-xs uppercase tracking-wide text-slate-500">Ingresos brutos</p>
                <p id="summary-item-revenue" class="mt-2 text-2xl font-semibold text-slate-900">Bs {{ number_format($itemRevenue, 2, ',', '.') }}</p>
            </div>
            <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-4">
                <p class="text-xs uppercase tracking-wide text-slate-500">Descuentos aplicados</p>
                <p id="summary-total-discount" class="mt-2 text-2xl font-semibold text-slate-900">Bs {{ number_format($totalDiscount ?? 0, 2, ',', '.') }}</p>
            </div>
        </div>
        <p class="mt-4 text-sm text-slate-500">Precio promedio por unidad: <span id="summary-average-price">Bs {{ number_format($averagePrice ?? 0, 2, ',', '.') }}</span></p>
        <p class="text-xs text-slate-400 mt-1">Los descuentos se restan del bruto; si no ingresó dinero, el total neto quedará en Bs 0,00.</p>
    </div>

    <div class="rounded-2xl bg-white/90 p-6 shadow-xl shadow-emerald-200/40">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-slate-900">Detalle por ítem</h2>
            <span id="items-count" class="text-sm text-slate-500">{{ $reportItems->count() }} ítems</span>
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
                <tbody id="items-body">
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
            <span id="promotions-count" class="text-sm text-slate-500">{{ $salesWithPromotions->count() }} registros</span>
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
                <tbody id="promotions-body">
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

@push('scripts')
<script>
(() => {
    const reportEndpoint = '{{ $reportEndpoint }}';
    const pdfLink = document.getElementById('report-pdf-link');
    const loadingOverlay = document.getElementById('report-loading');
    const reportForm = document.getElementById('report-form');
    const summaryQuantity = document.getElementById('summary-total-quantity');
    const summaryNet = document.getElementById('summary-total-income');
    const summaryGross = document.getElementById('summary-item-revenue');
    const summaryDiscount = document.getElementById('summary-total-discount');
    const summaryAverage = document.getElementById('summary-average-price');
    const periodLabel = document.getElementById('report-period-label');
    const itemLabel = document.getElementById('report-item-label');
    const itemsBody = document.getElementById('items-body');
    const promotionsBody = document.getElementById('promotions-body');
    const itemsCount = document.getElementById('items-count');
    const promotionsCount = document.getElementById('promotions-count');
    const applyButton = reportForm.querySelector('button[type="submit"]');
    const initialPayload = @json($initialAjaxPayload);

    const sanitizeNumber = (value) => {
        const numeric = Number(value);
        return Number.isFinite(numeric) ? numeric : 0;
    };

    const formatInteger = (value) => {
        return sanitizeNumber(value).toLocaleString('es-BO', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
    };

    const formatCurrency = (value) => {
        return `Bs ${sanitizeNumber(value).toLocaleString('es-BO', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
    };

    const renderItems = (items) => {
        if (!items.length) {
            itemsBody.innerHTML = `<tr><td colspan="4" class="px-3 py-6 text-center text-sm text-slate-500">No hay registros para el rango seleccionado.</td></tr>`;
            return;
        }

        itemsBody.innerHTML = items.map((item) => `
            <tr class="border-b border-slate-100 hover:bg-slate-50">
                <td class="px-3 py-3 font-semibold text-slate-900">${item.name}</td>
                <td class="px-3 py-3 text-right">${formatInteger(item.quantity)}</td>
                <td class="px-3 py-3 text-right">${formatCurrency(item.avg_price)}</td>
                <td class="px-3 py-3 text-right">${formatCurrency(item.revenue)}</td>
            </tr>
        `).join('');
    };

    const renderPromotions = (promotions) => {
        if (!promotions.length) {
            promotionsBody.innerHTML = `<tr><td colspan="6" class="px-3 py-6 text-center text-sm text-slate-500">No se registraron promociones en este rango.</td></tr>`;
            return;
        }

        promotionsBody.innerHTML = promotions.map((sale) => {
            const discountTypeLabel = sale.discount_type === 'percentage'
                ? `Porcentaje (${sale.discount_rate ?? 0}%)`
                : 'Monto fijo';
            const discountValueLabel = `${formatCurrency(sale.discount)}${sale.discount_type === 'percentage' && sale.discount_rate ? ` (${sale.discount_rate}%)` : ''}`;

            return `
                <tr class="border-b border-slate-100 hover:bg-slate-50">
                    <td class="px-3 py-3 font-semibold text-slate-900">${sale.invoice}</td>
                    <td class="px-3 py-3">${sale.date ?? '—'}</td>
                    <td class="px-3 py-3">${sale.promotion ?? '—'}</td>
                    <td class="px-3 py-3 text-right">${discountTypeLabel}</td>
                    <td class="px-3 py-3 text-right">${discountValueLabel}</td>
                    <td class="px-3 py-3 text-right">${formatCurrency(sale.total)}</td>
                </tr>
            `;
        }).join('');
    };

    const updateSummary = (summary) => {
        summaryQuantity.textContent = formatInteger(summary.totalQuantity);
        summaryNet.textContent = formatCurrency(summary.totalIncome);
        summaryGross.textContent = formatCurrency(summary.itemRevenue);
        summaryDiscount.textContent = formatCurrency(summary.totalDiscount);
        summaryAverage.textContent = formatCurrency(summary.averagePrice);
    };

    const updatePdfLink = (payload) => {
        if (!pdfLink) {
            return;
        }

        const url = new URL(pdfLink.dataset.baseUrl);
        url.searchParams.set('start_date', payload.startDate);
        url.searchParams.set('end_date', payload.endDate);
        if (payload.selectedItemId) {
            url.searchParams.set('item_id', payload.selectedItemId);
        } else {
            url.searchParams.delete('item_id');
        }

        pdfLink.href = url.toString();
    };

    const renderPayload = (payload) => {
        updateSummary(payload.summary);
        renderItems(payload.items);
        renderPromotions(payload.promotions);
        periodLabel.textContent = payload.periodLabel;
        itemLabel.textContent = payload.selectedItemLabel;
        itemsCount.textContent = `${payload.items.length} ítems`;
        promotionsCount.textContent = `${payload.promotions.length} registros`;
        updatePdfLink(payload);
    };

    const setLoading = (isLoading) => {
        if (!loadingOverlay) {
            return;
        }

        if (isLoading) {
            loadingOverlay.classList.remove('opacity-0', 'pointer-events-none', 'hidden');
            loadingOverlay.classList.add('opacity-100');
        } else {
            loadingOverlay.classList.add('opacity-0', 'pointer-events-none', 'hidden');
            loadingOverlay.classList.remove('opacity-100');
        }

        if (applyButton) {
            applyButton.disabled = isLoading;
        }
    };

    const fetchReport = async (formData) => {
        setLoading(true);
        try {
            const url = new URL(reportEndpoint, window.location.origin);
            for (const [key, value] of formData.entries()) {
                if (value) {
                    url.searchParams.set(key, value);
                } else {
                    url.searchParams.delete(key);
                }
            }

            const response = await fetch(url.toString(), {
                headers: {
                    'Accept': 'application/json',
                },
            });

            if (!response.ok) {
                throw new Error('Error al cargar el reporte');
            }

            const payload = await response.json();
            renderPayload(payload);
        } catch (error) {
            console.error(error);
            alert('No se pudo cargar el reporte. Intenta nuevamente.');
        } finally {
            setLoading(false);
        }
    };

    reportForm.addEventListener('submit', (event) => {
        event.preventDefault();
        fetchReport(new FormData(reportForm));
    });

    renderPayload(initialPayload);
})();
</script>
@endpush
