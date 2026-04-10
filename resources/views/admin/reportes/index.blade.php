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
                    @php
                        $pdfParams = ['start_date' => $startDate, 'end_date' => $endDate];
                        if ($selectedItemId) {
                            $pdfParams['item_id'] = $selectedItemId;
                        }
                        if ($selectedUserId) {
                            $pdfParams['user_id'] = $selectedUserId;
                        }
                    @endphp
                    <a id="report-pdf-link" data-base-url="{{ route('reportes.ventas.items.pdf') }}"
                        href="{{ route('reportes.ventas.items.pdf', $pdfParams) }}"
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

            <form id="report-form" method="GET" action="{{ route('reportes.index') }}" class="mt-6 grid gap-4 md:grid-cols-5 items-end">
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
                <label class="text-sm font-medium text-slate-700">
                    Usuario
                    <select name="user_id"
                        class="mt-1 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
                        <option value="">Todos</option>
                        @foreach($userOptions as $userOption)
                            <option value="{{ $userOption->id }}" {{ $userOption->id === $selectedUserId ? 'selected' : '' }}>
                                {{ $userOption->name }}
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
        <div class="mt-4 border border-slate-200 bg-slate-50/70 rounded-2xl px-4 py-2">
            <div class="flex flex-wrap items-center justify-between gap-4 text-sm font-semibold text-slate-700">
                <span class="uppercase tracking-wide">Cantidad total: <span id="summary-total-quantity">{{ number_format($totalQuantity, 0, ',', '.') }}</span></span>
                <span class="uppercase tracking-wide">Total: <span id="summary-total-income">Bs {{ number_format($totalIncome, 2, ',', '.') }}</span></span>
            </div>
        </div>
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
                        <th class="px-3 py-3 text-right">Ingreso</th>
                    </tr>
                </thead>
                <tbody id="items-body">
                    @forelse($reportItems as $item)
                        <tr class="border-b border-slate-100 hover:bg-slate-50">
                            <td class="px-3 py-3 font-semibold text-slate-900">{{ $item->name }}</td>
                            <td class="px-3 py-3 text-right">{{ number_format($item->quantity, 0, ',', '.') }}</td>
                            <td class="px-3 py-3 text-right">Bs {{ number_format($item->revenue, 2, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-3 py-6 text-center text-sm text-slate-500">
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
            <h2 class="text-lg font-semibold text-slate-900">Ventas registradas</h2>
            <span id="sales-count" class="text-sm text-slate-500">{{ count($initialAjaxPayload['salesList'] ?? []) }} ventas</span>
        </div>
        <div class="mt-4 overflow-x-auto">
            <table class="min-w-full border-separate border-spacing-0 text-sm text-slate-800">
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-3 py-3">Fecha</th>
                        <th class="px-3 py-3">Hora</th>
                        <th class="px-3 py-3">Ítem</th>
                        <th class="px-3 py-3 text-right">Cantidad</th>
                        <th class="px-3 py-3">Usuario</th>
                        <th class="px-3 py-3 text-right">Total</th>
                        <th class="px-3 py-3">Glosa</th>
                    </tr>
                </thead>
                <tbody id="sales-body">
                    @foreach($initialAjaxPayload['salesList'] ?? [] as $sale)
                        <tr class="border-b border-slate-100 hover:bg-slate-50">
                            <td class="px-3 py-3">{{ $sale['date'] }}</td>
                            <td class="px-3 py-3">{{ $sale['time'] }}</td>
                            <td class="px-3 py-3">{{ $sale['item'] }}</td>
                            <td class="px-3 py-3 text-right">{{ $sale['quantity'] }}</td>
                            <td class="px-3 py-3">{{ $sale['user'] }}</td>
                            <td class="px-3 py-3 text-right">Bs {{ number_format($sale['total'], 2, ',', '.') }}</td>
                            <td class="px-3 py-3">{{ $sale['glosa'] }}</td>
                        </tr>
                    @endforeach
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
    const periodLabel = document.getElementById('report-period-label');
    const itemLabel = document.getElementById('report-item-label');
    const itemsBody = document.getElementById('items-body');
    const itemsCount = document.getElementById('items-count');
    const salesBody = document.getElementById('sales-body');
    const salesCount = document.getElementById('sales-count');
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
            itemsBody.innerHTML = `<tr><td colspan="3" class="px-3 py-6 text-center text-sm text-slate-500">No hay registros para el rango seleccionado.</td></tr>`;
            return;
        }

        itemsBody.innerHTML = items.map((item) => `
            <tr class="border-b border-slate-100 hover:bg-slate-50">
                <td class="px-3 py-3 font-semibold text-slate-900">${item.name}</td>
                <td class="px-3 py-3 text-right">${formatInteger(item.quantity)}</td>
                <td class="px-3 py-3 text-right">${formatCurrency(item.revenue)}</td>
            </tr>
        `).join('');
    };

    const renderSales = (sales) => {
        if (!salesBody) {
            return;
        }

        if (!sales.length) {
            salesBody.innerHTML = `<tr><td colspan="5" class="px-3 py-6 text-center text-sm text-slate-500">No hay ventas registradas para el rango seleccionado.</td></tr>`;
            return;
        }

        salesBody.innerHTML = sales.map((sale) => `
            <tr class="border-b border-slate-100 hover:bg-slate-50">
                <td class="px-3 py-3">${sale.date}</td>
                <td class="px-3 py-3">${sale.time}</td>
                <td class="px-3 py-3">${sale.item}</td>
                <td class="px-3 py-3 text-right">${sale.quantity}</td>
                <td class="px-3 py-3">${sale.user}</td>
                <td class="px-3 py-3 text-right">${formatCurrency(sale.total)}</td>
                <td class="px-3 py-3">${sale.glosa ?? ''}</td>
            </tr>
        `).join('');
    };

    const updateSummary = (summary) => {
        summaryQuantity.textContent = formatInteger(summary.totalQuantity);
        summaryNet.textContent = formatCurrency(summary.totalIncome);
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
        if (payload.selectedUserId) {
            url.searchParams.set('user_id', payload.selectedUserId);
        } else {
            url.searchParams.delete('user_id');
        }

        pdfLink.href = url.toString();
    };

    const renderPayload = (payload) => {
        updateSummary(payload.summary);
        renderItems(payload.items);
        periodLabel.textContent = payload.periodLabel;
        itemLabel.textContent = payload.selectedItemLabel;
        itemsCount.textContent = `${payload.items.length} ítems`;
        renderSales(payload.salesList || []);
        if (salesCount) {
            salesCount.textContent = `${(payload.salesList || []).length} ventas`;
        }
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
