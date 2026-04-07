<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte de ventas por ítem</title>
    <style>
        @page {
            size: 11in 14in;
            margin: 10mm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
            color: #000;
            margin: 0;
        }

        .invoice {
            width: 100%;
            padding: 0 10px;
        }

        .separator {
            border-bottom: 1px dashed #999;
            margin: 8px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 4px 2px;
        }

        thead th {
            border-bottom: 1px solid #000;
            text-align: left;
        }

        tbody td {
            border-bottom: 1px dashed #ccc;
        }

        .right {
            text-align: right;
        }

        .center {
            text-align: center;
        }

        .totals td {
            padding: 3px 0;
        }

        footer {
            margin-top: 15px;
            text-align: center;
            font-size: 9px;
        }
    </style>
</head>

<body>
    @php
        $logoPath = public_path('img/logo.png');
        $logoData = file_exists($logoPath)
            ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath))
            : null;
        $rangeLabel = \Illuminate\Support\Carbon::parse($startDate)->format('d/m/Y') . ' - ' . \Illuminate\Support\Carbon::parse($endDate)->format('d/m/Y');
    @endphp

    <div class="invoice">
        <table>
            <tr>
                <td width="15%">
                    @if ($logoData)
                        <img src="{{ $logoData }}" width="50">
                    @endif
                </td>
                <td width="85%">
                    <strong style="font-size:16px;">COLCAPUJLLAY</strong><br>
                    <span>Parque natural · Colcapirhua</span>
                </td>
            </tr>
        </table>

        <div class="separator"></div>

        <p><strong>Reporte:</strong> Ventas por ítem</p>
        <p><strong>Período:</strong> {{ $rangeLabel }}</p>
        <p><strong>Ítem:</strong> {{ $selectedItemLabel }}</p>

        <table style="margin-top:10px;">
            <thead>
                <tr>
                    <th>Ítem</th>
                    <th class="right">Cantidad</th>
                    <th class="right">Precio promedio</th>
                    <th class="right">Ingreso</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($reportItems as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td class="right">{{ number_format($item->quantity, 0, ',', '.') }}</td>
                        <td class="right">Bs {{ number_format($item->avg_price ?? 0, 2, ',', '.') }}</td>
                        <td class="right">Bs {{ number_format($item->revenue, 2, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="center">No hay datos para el periodo seleccionado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <table class="totals" style="margin-top:10px;">
            <tr>
                <td>Total cantidad</td>
                <td class="right">{{ number_format($totalQuantity, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Total ingresos</td>
                <td class="right">Bs {{ number_format($totalIncome, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Precio promedio general</td>
                <td class="right">Bs {{ number_format($averagePrice, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Ingresos antes de descuentos (ítem)</td>
                <td class="right">Bs {{ number_format($itemRevenue, 2, ',', '.') }}</td>
            </tr>
        </table>

        <div class="separator"></div>
        <p><strong>Notas:</strong> Los descuentos no se suman al ingreso neto; si no ingresó dinero, se muestra Bs 0,00.</p>

        <table style="margin-top:10px;">
            <thead class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                <tr>
                    <th>Factura</th>
                    <th>Fecha</th>
                    <th>Promoción</th>
                    <th class="right">Tipo</th>
                    <th class="right">Descuento</th>
                    <th class="right">Total neto</th>
                </tr>
            </thead>
            <tbody>
                @forelse($salesWithPromotions as $sale)
                    <tr>
                        <td>{{ $sale->formatted_invoice_number }}</td>
                        <td>{{ optional($sale->sale_date)->format('d/m/Y H:i') }}</td>
                        <td>{{ $sale->promotion?->name ?? '—' }}</td>
                        <td class="right">{{ $sale->promotion?->discount_type === 'percentage' ? 'Porcentaje' : 'Monto fijo' }}</td>
                        <td class="right">Bs {{ number_format($sale->discount_amount, 2, ',', '.') }}</td>
                        <td class="right">Bs {{ number_format($sale->total, 2, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="center">No se aplicaron promociones en este periodo.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <footer>
            Gracias por su visita a Parque Collcapujllay<br>
            Conserve este comprobante
        </footer>
    </div>
</body>

</html>
