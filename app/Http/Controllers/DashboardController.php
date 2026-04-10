<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $now = now();

        $today = $now->toDateString();
        $month = $now->month;
        $year = $now->year;

        $weekStart = $now->copy()->subDays(6)->startOfDay();
        $weekEnd = $now->copy()->endOfDay();

        // 🔥 BASE QUERY reutilizable
        $baseQuery = Sale::where('status', Sale::STATUS_ACTIVE);

        // =========================
        // 📊 RESUMEN (1 SOLA QUERY)
        // =========================
        $summaryData = (clone $baseQuery)
            ->selectRaw("
            COUNT(CASE WHEN DATE(sale_date) = ? THEN 1 END) as sales_today_count,
            SUM(CASE WHEN DATE(sale_date) = ? THEN total ELSE 0 END) as sales_today_revenue,
            COUNT(*) as monthly_sales_count,
            SUM(total) as monthly_revenue
        ", [$today, $today])
            ->whereMonth('sale_date', $month)
            ->whereYear('sale_date', $year)
            ->first();

        $averageTicket = $summaryData->monthly_sales_count > 0
            ? $summaryData->monthly_revenue / $summaryData->monthly_sales_count
            : 0;

        $summary = [
            'sales_today_count' => (int) $summaryData->sales_today_count,
            'sales_today_revenue' => (float) $summaryData->sales_today_revenue,
            'monthly_sales_count' => (int) $summaryData->monthly_sales_count,
            'monthly_revenue' => (float) $summaryData->monthly_revenue,
            'average_ticket' => (float) $averageTicket,
        ];

        // =========================
        // 📈 TENDENCIA SEMANAL
        // =========================
        $trendRaw = (clone $baseQuery)
            ->whereBetween('sale_date', [$weekStart, $weekEnd])
            ->selectRaw('DATE(sale_date) as date, SUM(total) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        $salesTrend = collect(range(6, 0))->map(function ($daysAgo) use ($now, $trendRaw) {
            $date = $now->copy()->subDays($daysAgo);
            $key = $date->format('Y-m-d');

            return [
                'label' => $date->format('d/m'),
                'amount' => (float) ($trendRaw[$key] ?? 0),
            ];
        });

        // =========================
        // 🏆 TOP ITEMS
        // =========================
        $topItems = SaleItem::query()
            ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->join('items', 'items.id', '=', 'sale_items.item_id')
            ->where('sales.status', Sale::STATUS_ACTIVE)
            ->selectRaw('items.name as item_name, SUM(sale_items.quantity) as quantity, SUM(sale_items.total) as revenue')
            ->groupBy('items.name')
            ->orderByDesc('revenue')
            ->limit(4)
            ->get();

        // =========================
        // 🧾 VENTAS RECIENTES
        // =========================
        $recentSales = (clone $baseQuery)
            ->latest('sale_date')
            ->limit(5)
            ->get();

        // =========================
        // 👥 GLOBAL
        // =========================
        $usersCount = User::count();
        $totalSales = (clone $baseQuery)->count();

        return view('admin.index', compact(
            'summary',
            'salesTrend',
            'topItems',
            'recentSales',
            'usersCount',
            'totalSales'
        ));
    }
}
