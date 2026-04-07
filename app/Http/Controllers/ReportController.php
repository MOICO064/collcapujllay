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
        $selectedItemLabel = $selectedItem?->name ?? 'Todos';

        $reportItems = $this->itemReportQuery($startDate, $endDate, $selectedItem?->id)
            ->orderByDesc('revenue')
            ->get();

        $totalQuantity = (float) $reportItems->sum('quantity');
        $itemRevenue = (float) $reportItems->sum('revenue');
        $averagePrice = $totalQuantity > 0 ? $itemRevenue / $totalQuantity : 0;

        $salesBaseQuery = $this->salesBaseQuery($startDate, $endDate, $selectedItem?->id);
        $totalIncome = (float) $salesBaseQuery->clone()->sum('total');
        $salesWithPromotions = $salesBaseQuery->clone()
            ->with('promotion')
            ->whereNotNull('promotion_id')
            ->orderBy('sale_date')
            ->get();

        $periodLabel = Carbon::parse($startDate)->format('d/m/Y') . ' - ' . Carbon::parse($endDate)->format('d/m/Y');

        return view('admin.reportes.index', [
            'reportItems' => $reportItems,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalQuantity' => $totalQuantity,
            'itemRevenue' => $itemRevenue,
            'totalIncome' => $totalIncome,
            'averagePrice' => $averagePrice,
            'periodLabel' => $periodLabel,
            'itemOptions' => $itemOptions,
            'selectedItemLabel' => $selectedItemLabel,
            'selectedItemId' => $selectedItem?->id,
            'salesWithPromotions' => $salesWithPromotions,
        ]);
    }

    public function itemSalesPdf(Request $request)
    {
        $startDate = $request->query('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->query('end_date', now()->toDateString());
        $itemId = $request->query('item_id');
        $selectedItem = $itemId ? Item::find((int) $itemId) : null;
        $selectedItemLabel = $selectedItem?->name ?? 'Todos';

        $reportItems = $this->itemReportQuery($startDate, $endDate, $selectedItem?->id)
            ->orderByDesc('revenue')
            ->get();

        $totalQuantity = (float) $reportItems->sum('quantity');
        $itemRevenue = (float) $reportItems->sum('revenue');
        $averagePrice = $totalQuantity > 0 ? $itemRevenue / $totalQuantity : 0;
        $periodLabel = Carbon::parse($startDate)->format('d/m/Y') . ' - ' . Carbon::parse($endDate)->format('d/m/Y');

        $salesBaseQuery = $this->salesBaseQuery($startDate, $endDate, $selectedItem?->id);
        $totalIncome = (float) $salesBaseQuery->clone()->sum('total');
        $salesWithPromotions = $salesBaseQuery->clone()
            ->with('promotion')
            ->whereNotNull('promotion_id')
            ->orderBy('sale_date')
            ->get();

        $pdf = Pdf::loadView('admin.reportes.pdf.items', [
            'reportItems' => $reportItems,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalQuantity' => $totalQuantity,
            'itemRevenue' => $itemRevenue,
            'totalIncome' => $totalIncome,
            'averagePrice' => $averagePrice,
            'periodLabel' => $periodLabel,
            'selectedItemLabel' => $selectedItemLabel,
            'salesWithPromotions' => $salesWithPromotions,
        ])->setPaper([0, 0, 792, 1008])
            ->setOption('isRemoteEnabled', false)
            ->setOption('dpi', 72);

        $fileName = 'reporte-ventas-items-' . Carbon::now()->format('Ymd') . '.pdf';

        return $pdf->stream($fileName);
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
            ->when($itemId, fn ($query) => $query->where('items.id', $itemId))
            ->groupBy('items.id', 'items.name');
    }

    private function salesBaseQuery(string $startDate, string $endDate, ?int $itemId = null)
    {
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        $query = Sale::where('status', 'active')
            ->whereBetween('sale_date', [$start, $end]);

        if ($itemId) {
            $query->whereHas('saleItems', fn ($sub) => $sub->where('item_id', $itemId));
        }

        return $query;
    }
}
