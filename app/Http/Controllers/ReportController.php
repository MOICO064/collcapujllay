<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Sale;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->query('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->query('end_date', now()->toDateString());
        $itemId = $request->query('item_id');

        $itemOptions = Item::select(['id', 'name'])->orderBy('name')->get();
        $selectedItem = $itemId ? $itemOptions->firstWhere('id', (int) $itemId) : null;

        $reportData = $this->collectReportData($startDate, $endDate, $selectedItem);
        $initialAjaxPayload = $this->buildAjaxPayload($reportData, $startDate, $endDate);

        return view('admin.reportes.index', array_merge($reportData, [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'itemOptions' => $itemOptions,
            'initialAjaxPayload' => $initialAjaxPayload,
            'reportEndpoint' => route('reportes.data'),
        ]));
    }

    public function data(Request $request)
    {
        $startDate = $request->query('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->query('end_date', now()->toDateString());
        $itemId = $request->query('item_id');
        $selectedItem = $itemId ? Item::find((int) $itemId) : null;

        $reportData = $this->collectReportData($startDate, $endDate, $selectedItem);

        return response()->json($this->buildAjaxPayload($reportData, $startDate, $endDate));
    }

    public function itemSalesPdf(Request $request)
    {
        $startDate = $request->query('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->query('end_date', now()->toDateString());
        $itemId = $request->query('item_id');

        $selectedItem = $itemId ? Item::find((int) $itemId) : null;

        $reportData = $this->collectReportData($startDate, $endDate, $selectedItem);
        $payload = $this->buildAjaxPayload($reportData, $startDate, $endDate);

        $pdf = Pdf::loadView('admin.reportes.pdf.items', array_merge($reportData, [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'selectedItemLabel' => $selectedItem?->name ?? 'Todos',
            'payload' => $payload,
        ]))
            ->setPaper('letter', 'portrait')
            ->setOption('isRemoteEnabled', false)
            ->setOption('dpi', 72);

        $fileName = 'reporte-ventas-items-' . Carbon::now()->format('Ymd');

        return $pdf->stream($fileName . '.pdf');
    }

    private function collectReportData(string $startDate, string $endDate, ?Item $selectedItem = null): array
    {
        $itemId = $selectedItem?->id;

        $reportItems = $this->itemReportQuery($startDate, $endDate, $itemId)
            ->orderByDesc('revenue')
            ->get();

        $totalQuantity = (float) $reportItems->sum('quantity');
        $itemRevenue = (float) $reportItems->sum('revenue');

        $salesBaseQuery = $this->salesBaseQuery($startDate, $endDate, $itemId);

        $totalIncome = (float) (clone $salesBaseQuery)->sum('total');

        $totalDiscount = (float) (clone $salesBaseQuery)
            ->select(DB::raw('COALESCE(SUM(subtotal - total), 0) as discount_total'))
            ->value('discount_total');
        $totalDiscount = max(0, $totalDiscount);

        $averagePrice = $totalQuantity > 0 ? $itemRevenue / $totalQuantity : 0;

        $salesWithPromotions = (clone $salesBaseQuery)
            ->with('promotion')
            ->whereNotNull('promotion_id')
            ->orderBy('sale_date')
            ->get();

        $periodLabel = Carbon::parse($startDate)->format('d/m/Y') . ' - ' . Carbon::parse($endDate)->format('d/m/Y');

        return [
            'reportItems' => $reportItems,
            'salesWithPromotions' => $salesWithPromotions,

            'totalQuantity' => $totalQuantity,
            'itemRevenue' => $itemRevenue,

            'totalIncome' => $totalIncome,     
            'totalDiscount' => $totalDiscount, 
            'averagePrice' => $averagePrice,

            'periodLabel' => $periodLabel,
            'selectedItemLabel' => $selectedItem?->name ?? 'Todos',
            'selectedItemId' => $itemId,
        ];
    }

    private function buildAjaxPayload(array $data, string $startDate, string $endDate): array
    {
        return [
            'summary' => [
                'totalQuantity' => $data['totalQuantity'] ?? 0,
                'itemRevenue' => $data['itemRevenue'] ?? 0,
                'totalIncome' => $data['totalIncome'] ?? 0,
                'totalDiscount' => $data['totalDiscount'] ?? 0,
                'averagePrice' => $data['averagePrice'] ?? 0,
            ],
            'items' => $data['reportItems']->map(fn($item) => [
                'id' => (int) $item->id,
                'name' => $item->name,
                'quantity' => (float) $item->quantity,
                'avg_price' => (float) $item->avg_price,
                'revenue' => (float) $item->revenue,
            ])->values(),
            'promotions' => $data['salesWithPromotions']->map(fn($sale) => [
                'invoice' => $sale->formatted_invoice_number,
                'date' => $sale->sale_date?->format('d/m/Y H:i'),
                'promotion' => $sale->promotion?->name,
            'discount_type' => $sale->promotion?->discount_type,
            'discount' => (float) $sale->discount_amount,
            'discount_rate' => (float) ($sale->promotion?->discount_value ?? 0),
            'total' => (float) $sale->total,
        ])->values(),
            'periodLabel' => $data['periodLabel'] ?? null,
            'selectedItemLabel' => $data['selectedItemLabel'] ?? 'Todos',
            'selectedItemId' => $data['selectedItemId'] ?? null,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ];
    }

    private function itemReportQuery(string $startDate, string $endDate, ?int $itemId = null)
    {
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        return Item::select([
            'items.id',
            'items.name',
            DB::raw('SUM(sale_items.quantity) as quantity'),
            DB::raw('SUM(sale_items.total) as revenue'),
            DB::raw('AVG(sale_items.unit_price) as avg_price'),
        ])
            ->join('sale_items', 'sale_items.item_id', '=', 'items.id')
            ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->where('sales.status', 'active')
            ->whereBetween('sales.sale_date', [$start, $end])
            ->when($itemId, fn($query) => $query->where('items.id', $itemId))
            ->groupBy('items.id', 'items.name');
    }

    private function salesBaseQuery(string $startDate, string $endDate, ?int $itemId = null)
    {
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        $query = Sale::where('status', 'active')
            ->whereBetween('sale_date', [$start, $end]);

        if ($itemId) {
            $query->whereHas('saleItems', fn($sub) => $sub->where('item_id', $itemId));
        }

        return $query;
    }
}
