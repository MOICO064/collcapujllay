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

    $paymentMethodLabel = ucfirst($venta->payment_method ?? 'efectivo');
    $discountAmount = $venta->discount_amount;
    @endphp

    <style>
        @page {
            size: 12cm 16.5cm;
            margin: 0;
        }

        html,
        body {
            width: 12cm;
            height: 16.5cm;
            margin: 0;
            padding: 0;
        }

        html,
        body {
            width: 11cm;
            height: 16.5cm;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9px;
            color: #000;
            background: #fff;
            display: flex;
            justify-content: flex-start;
            align-items: flex-start;
            box-sizing: border-box;
            padding: 0;
        }

        .invoice-wrapper {
            width: 9cm;
            height: 16.5cm;
            box-sizing: border-box;
            padding: 10px;
            padding-right: calc(3cm + 10px);
        }

        .invoice {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
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
            padding: 2px 4px;
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
            margin-top: 4px;
            text-align: center;
            font-size: 8px;
        }
    </style>
</head>

<body>
    <div class="invoice-wrapper">
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
                    <strong>COMPROBANTE</strong><br>
                    #{{ $venta->formatted_invoice_number }}
                </td>
                <td class="right">
                    {{ $venta->sale_date?->format('d/m/Y') }}<br>
                    {{ $venta->sale_date?->format('H:i') }}
                    @if($venta->customer_code)
                        <br><strong>Cód. cliente:</strong> {{ $venta->customer_code }}
                    @endif
                </td>
            </tr>
        </table>

        <div class="separator"></div>

        <!-- INFO -->
        <p><strong>Método de pago:</strong> {{ $paymentMethodLabel }}</p>
        @if($venta->user)
        <p><strong>Usuario:</strong> {{ $venta->user->name }}</p>
        @endif
        @if($venta->glosa)
        <p><strong>Glosa:</strong> {{ $venta->glosa }}</p>
        @endif

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
                    <td>
                        {{ $line->item?->name ?? 'Ítem eliminado' }}
                        @if($line->item?->use_once && $line->use_once_number)
                            <div class="text-xs text-slate-500">Nº único: {{ $line->use_once_number }}</div>
                        @endif
                    </td>
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
                <td><strong>Total</strong></td>
                <td class="right"><strong>Bs {{ number_format($venta->total, 2, ',', '.') }}</strong></td>
            </tr>
        </table>

        <!-- FOOTER -->
        <footer>
            Gracias por su visita a Parque Collcapujllay<br>
            COMPROBANTE NO TRIBUTARIO - Este comprobante no tiene validez tributaria.
        </footer>

        </div>
    </div>
</body>

</html>
