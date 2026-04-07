<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Factura {{ $venta->formatted_invoice_number }}</title>

    @php
    $logoPath = public_path('img/logo.png');
    $logoData = file_exists($logoPath)
    ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath))
    : null;

    $promotionName = $venta->promotion?->name;
    $discountTypeLabel = $venta->promotion
    ? ($venta->promotion->discount_type === 'percentage' ? 'Porcentaje' : 'Monto fijo') . ' · ' . $promotionName
    : 'Sin promoción';
    $discountAmount = $venta->discount_amount;
    @endphp

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
    <div class="invoice">

        <!-- HEADER -->
        <table>
            <tr>
                <td width="15%">
                    @if($logoData)
                    <img src="{{ $logoData }}" width="50">
                    @endif
                </td>
                <td width="85%">
                    <strong style="font-size:16px;">COLCAPUJLLAY</strong><br>
                    <span>Parque natural · Colcapirhua</span>
                </td>
            </tr>
        </table>

        <!-- META -->
        <table style="margin-top:8px;">
            <tr>
                <td>
                    <strong>FACTURA</strong><br>
                    #{{ $venta->formatted_invoice_number }}
                </td>
                <td class="right">
                    {{ $venta->sale_date?->format('d/m/Y') }}<br>
                    {{ $venta->sale_date?->format('H:i') }}
                </td>
            </tr>
        </table>

        <div class="separator"></div>

        <!-- INFO -->
        <p><strong>Promoción:</strong> {{ $discountTypeLabel }}</p>
        <p><strong>Cliente:</strong> {{ $venta->customer_ci ?? 'Consumidor final' }}</p>

        <!-- ITEMS -->
        <table>
            <thead>
                <tr>
                    <th>Ítem</th>
                    <th class="right">Cant.</th>
                    <th class="right">Unit.</th>
                    <th class="right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($venta->saleItems as $line)
                <tr>
                    <td>{{ $line->item?->name ?? 'Ítem eliminado' }}</td>
                    <td class="right">{{ $line->quantity }}</td>
                    <td class="right">Bs {{ number_format($line->unit_price, 2, ',', '.') }}</td>
                    <td class="right">Bs {{ number_format($line->total, 2, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- TOTALES -->
        <table class="totals" style="margin-top:10px;">
            <tr>
                <td>Subtotal</td>
                <td class="right">Bs {{ number_format($venta->subtotal, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Descuento</td>
                <td class="right">
                    Bs {{ number_format($discountAmount, 2, ',', '.') }}
                    @if($venta->discount_type === 'percentage' && $venta->discount_value > 0)
                        <br><span style="font-size:9px;">({{ number_format($venta->discount_value, 2, ',', '.') }} %)</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td><strong>Total</strong></td>
                <td class="right"><strong>Bs {{ number_format($venta->total, 2, ',', '.') }}</strong></td>
            </tr>
        </table>

        <!-- FOOTER -->
        <footer>
            Gracias por su visita a Parque Collcapujllay<br>
            Conserve este comprobante
        </footer>

    </div>
</body>

</html>
