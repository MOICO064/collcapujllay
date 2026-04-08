<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte de ventas por ítem</title>
    <style>
        @page {
            size: letter portrait;
            margin: 12mm;
        }

        body {
            font-family: 'Inter', 'Arial', sans-serif;
            font-size: 9px;
            margin: 0;
            color: #0b1824;
            background: #fff;
        }

        .report {
            width: 100%;
            padding: 0;
            box-sizing: border-box;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
            padding-bottom: 6px;
            border-bottom: 1px solid #dfe5ee;
        }

        .header-left {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .logo {
            width: 50px;
            height: 50px;
            object-fit: contain;
            border-radius: 8px;
        }

        .title {
            font-size: 14px;
            font-weight: 700;
        }

        .eyebrow {
            font-size: 8px;
            color: #555b63;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.2em;
        }

        .meta {
            text-align: right;
            font-size: 9px;
            line-height: 1.2;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 6px;
            margin-bottom: 10px;
        }

        .summary-card {
            background: #ffffff;
            border: 1px solid #d6dee6;
            border-radius: 8px;
            padding: 6px 8px;
            text-align: center;
        }

        .summary-card .label {
            font-size: 8px;
            color: #6b7789;
            text-transform: uppercase;
        }

        .summary-card strong {
            display: block;
            margin-top: 4px;
            font-size: 12px;
        }

        .section-title {
            margin-top: 12px;
            margin-bottom: 4px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            padding: 4px 6px;
            background: #f0f4f8;
            border: 1px solid #dce5ef;
            border-radius: 6px;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5px;
            table-layout: fixed;
            word-wrap: break-word;
            margin-bottom: 4px;
        }

        table.data-table th,
        table.data-table td {
            border: 1px solid #dfe5ee;
            padding: 4px 6px;
            overflow-wrap: break-word;
        }

        table.data-table th {
            background: #eef1f6;
            font-weight: 600;
            text-align: left;
        }

        table.data-table td.text-right {
            text-align: right;
        }

        .total-row td {
            font-weight: 600;
            background: #f3f6fa;
        }

        .footer {
            margin-top: 12px;
            font-size: 7px;
            text-align: center;
            color: #6b7485;
        }

        .summary-note {
            font-size: 8px;
            color: #4a5568;
            margin-top: 4px;
        }

        tr {
            page-break-inside: avoid;
        }
    </style>
</head>

<body>
    @php
    $logoPath = public_path('img/logo.png');
    $logoData = file_exists($logoPath)
    ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath))
    : null;

    $payload = $payload ?? [];
    $summary = $payload['summary'] ?? [];
    $items = $payload['items'] ?? [];
    $promotions = $payload['promotions'] ?? [];

    $netIncome = (float) ($summary['totalIncome'] ?? 0);
    $grossIncome = (float) ($summary['itemRevenue'] ?? 0);
    $discountTotal = (float) ($summary['totalDiscount'] ?? 0);
    $averagePrice = (float) ($summary['averagePrice'] ?? 0);
    @endphp

    <div class="report">
        <div class="header">
            <div class="header-left">
                @if ($logoData)
                <img src="{{ $logoData }}" alt="Logo" class="logo">
                @endif
                <div class="eyebrow">Parque natural · Colcapirhua</div>
                <div class="title">REPORTE DE VENTAS POR ÍTEM</div>
            </div>
            <div class="meta">
                <div><strong>Período:</strong> {{ $periodLabel }}</div>
                <div><strong>Ítem:</strong> {{ $selectedItemLabel }}</div>
                <div>{{ now()->format('d/m/Y H:i') }}</div>
            </div>
        </div>

        <div class="summary-grid">
            <div class="summary-card">
                <div class="label">Cantidad total</div>
                <strong>{{ number_format($summary['totalQuantity'] ?? 0, 0, ',', '.') }}</strong>
            </div>
            <div class="summary-card">
                <div class="label">Ingresos netos</div>
                <strong>Bs {{ number_format($netIncome, 2, ',', '.') }}</strong>
            </div>
            <div class="summary-card">
                <div class="label">Ingresos brutos</div>
                <strong>Bs {{ number_format($grossIncome, 2, ',', '.') }}</strong>
            </div>
            <div class="summary-card">
                <div class="label">Descuentos aplicados</div>
                <strong>Bs {{ number_format($discountTotal, 2, ',', '.') }}</strong>
            </div>
        </div>
        <div class="summary-note">Precio promedio por unidad: <strong>Bs {{ number_format($averagePrice, 2, ',', '.') }}</strong>. Los descuentos se restan del ingreso bruto; el total neto no puede ser negativo.</div>

        <div class="section-title">Detalle por ítem</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Ítem</th>
                    <th class="text-right">Cantidad</th>
                    <th class="text-right">Precio promedio</th>
                    <th class="text-right">Ingreso</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td class="text-right">{{ number_format($item['quantity'], 0, ',', '.') }}</td>
                    <td class="text-right">Bs {{ number_format($item['avg_price'] ?? 0, 2, ',', '.') }}</td>
                    <td class="text-right">Bs {{ number_format($item['revenue'], 2, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">Sin datos</td>
                </tr>
                @endforelse
                <tr class="total-row">
                    <td>Total</td>
                    <td class="text-right">{{ number_format($summary['totalQuantity'] ?? 0, 0, ',', '.') }}</td>
                    <td class="text-right">Bs {{ number_format($averagePrice, 2, ',', '.') }}</td>
                    <td class="text-right">Bs {{ number_format($grossIncome, 2, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <div class="section-title">Ventas con promociones</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Factura</th>
                    <th>Fecha</th>
                    <th>Promoción</th>
                    <th>Tipo</th>
                    <th class="text-right">Descuento</th>
                    <th class="text-right">Total neto</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($promotions as $sale)
                <tr>
                    <td>{{ $sale['invoice'] }}</td>
                    <td>{{ $sale['date'] ?? '—' }}</td>
                    <td>{{ $sale['promotion'] ?? '—' }}</td>
                    <td class="text-right">
                        @if ($sale['discount_type'] === 'percentage')
                        Porcentaje ({{ number_format($sale['discount_rate'] ?? 0, 0, ',', '.') }}%)
                        @else
                        Monto fijo
                        @endif
                    </td>
                    <td class="text-right">
                        Bs {{ number_format($sale['discount'], 2, ',', '.') }}
                    </td>
                    <td class="text-right">Bs {{ number_format($sale['total'], 2, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Sin promociones</td>
                </tr>
                @endforelse
                <tr class="total-row">
                    <td colspan="4">Total descuentos</td>
                    <td class="text-right">Bs {{ number_format($discountTotal, 2, ',', '.') }}</td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        <div class="footer">
            Reporte generado · {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>
</body>

</html>