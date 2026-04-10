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
        }

        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
            padding-bottom: 4px;
            border-bottom: 1px solid #dfe5ee;
        }

        .logo {
            width: 45px;
            height: 45px;
        }

        .title {
            font-size: 13px;
            font-weight: 700;
        }

        .eyebrow {
            font-size: 8px;
            color: #555;
            text-transform: uppercase;
            letter-spacing: 0.15em;
        }

        .meta {
            text-align: right;
            font-size: 8.5px;
        }

        .section-title {
            margin-top: 8px;
            margin-bottom: 3px;
            font-size: 9px;
            font-weight: 700;
            padding: 3px 5px;
            background: #eef1f6;
            border: 1px solid #dce5ef;
            border-radius: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5px;
        }

        th,
        td {
            border: 1px solid #dfe5ee;
            padding: 4px 6px;
        }

        th {
            background: #eef1f6;
            font-weight: 600;
        }

        .text-right {
            text-align: right;
        }

        .total-row td {
            font-weight: 600;
            background: #f3f6fa;
        }

        .summary-table td {
            border: 1px solid #d6dee6;
            padding: 6px 8px;
        }

        .summary-label {
            font-size: 8px;
            color: #6b7789;
        }

        .summary-value {
            font-size: 11px;
            font-weight: 700;
        }

        .footer {
            margin-top: 10px;
            font-size: 7px;
            text-align: center;
            color: #6b7485;
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

    $totalQuantity = (float) ($summary['totalQuantity'] ?? 0);
    $totalGenerated = (float) ($summary['totalIncome'] ?? 0);
    @endphp

    <!-- HEADER -->
    <div class="header">
        <div>
            @if ($logoData)
            <img src="{{ $logoData }}" class="logo">
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

    <!-- RESUMEN BONITO EN UNA FILA -->
    <table class="summary-table" style="margin-bottom:8px;">
        <tr style="background:#f8fafc;">
            <td style="width:50%;">
                <div class="summary-label">Ítems vendidos</div>
                <div class="summary-value">
                    {{ number_format($totalQuantity, 0, ',', '.') }}
                </div>
            </td>

            <td style="width:50%; text-align:right;">
                <div class="summary-label">Total generado</div>
                <div class="summary-value">
                    Bs {{ number_format($totalGenerated, 2, ',', '.') }}
                </div>
            </td>
        </tr>
    </table>

    <!-- DETALLE ITEMS -->
    <div class="section-title">Detalle por ítem</div>

    <table>
        <thead>
            <tr>
                <th>Ítem</th>
                <th class="text-right">Cantidad</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($items as $item)
            <tr>
                <td>{{ $item['name'] }}</td>
                <td class="text-right">{{ number_format($item['quantity'], 0, ',', '.') }}</td>
                <td class="text-right">Bs {{ number_format($item['revenue'], 2, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center">Sin datos</td>
            </tr>
            @endforelse

            <tr class="total-row">
                <td>Total</td>
                <td class="text-right">{{ number_format($totalQuantity, 0, ',', '.') }}</td>
                <td class="text-right">Bs {{ number_format($totalGenerated, 2, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <!-- DETALLE VENTAS -->
    <div class="section-title">Ventas registradas</div>

    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Ítem</th>
                <th class="text-right">Cantidad</th>
                <th>Usuario</th>
                <th class="text-right">Total</th>
                <th>Glosa</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($payload['salesList'] ?? [] as $sale)
            <tr>
                <td>{{ $sale['date'] }}</td>
                <td>{{ $sale['time'] }}</td>
                <td>{{ $sale['item'] }}</td>
                <td class="text-right">{{ $sale['quantity'] }}</td>
                <td>{{ $sale['user'] }}</td>
                <td class="text-right">Bs {{ number_format($sale['total'], 2, ',', '.') }}</td>
                <td>{{ $sale['glosa'] ?? '' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Sin registros</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- FOOTER -->
    <div class="footer">
        Reporte generado · {{ now()->format('d/m/Y H:i') }}
    </div>

</body>

</html>