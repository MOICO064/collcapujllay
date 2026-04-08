<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use App\Models\PromotionUsage;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $now = now();
        $todayDate = $now->toDateString();
        $monthlyStart = $now->copy()->startOfMonth();
        $weekStart = $now->copy()->subDays(6)->startOfDay();
        $weekEnd = $now->copy()->endOfDay();
        $weeklyRange = [$weekStart, $weekEnd];

        $salesTodayCount = Sale::where('status', 'active')
            ->whereDate('sale_date', $todayDate)
            ->count();

        $salesTodayRevenue = Sale::where('status', 'active')
            ->whereDate('sale_date', $todayDate)
            ->sum('total');

        $monthlySalesCount = Sale::where('status', 'active')
            ->whereMonth('sale_date', $monthlyStart->month)
            ->whereYear('sale_date', $monthlyStart->year)
            ->count();

        $monthlyRevenue = Sale::where('status', 'active')
            ->whereMonth('sale_date', $monthlyStart->month)
            ->whereYear('sale_date', $monthlyStart->year)
            ->sum('total');

        $averageTicket = $monthlySalesCount > 0 ? $monthlyRevenue / $monthlySalesCount : 0;

        $summary = [
            'sales_today_count' => $salesTodayCount,
            'sales_today_revenue' => $salesTodayRevenue,
            'monthly_revenue' => $monthlyRevenue,
            'monthly_sales_count' => $monthlySalesCount,
            'average_ticket' => $averageTicket,
        ];

        $trendRaw = Sale::where('status', 'active')
            ->whereBetween('sale_date', $weeklyRange)
            ->selectRaw('DATE(sale_date) as date, SUM(total) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        $salesTrend = collect(range(6, 0))
            ->map(function ($daysAgo) use ($now, $trendRaw) {
                $date = $now->copy()->subDays($daysAgo);
                $key = $date->format('Y-m-d');

                return [
                    'label' => $date->format('d/m'),
                    'amount' => (float) ($trendRaw[$key] ?? 0),
                ];
            });

        $topItems = SaleItem::select('items.name as item_name', DB::raw('SUM(sale_items.quantity) as quantity'), DB::raw('SUM(sale_items.total) as revenue'))
            ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->join('items', 'items.id', '=', 'sale_items.item_id')
            ->where('sales.status', 'active')
            ->groupBy('items.name')
            ->orderByDesc('revenue')
            ->limit(4)
            ->get();

        $recentSales = Sale::with('promotion')
            ->where('status', 'active')
            ->orderBy('sale_date', 'desc')
            ->limit(5)
            ->get();

        $promotionUsage = PromotionUsage::with('promotion', 'sale')
            ->whereHas('sale', fn ($query) => $query->where('status', 'active'))
            ->latest('used_at')
            ->limit(4)
            ->get();

        $activePromotions = Promotion::whereDate('start_date', '<=', $now)
            ->whereDate('end_date', '>=', $now)
            ->count();

        $totalPromotionUsage = PromotionUsage::count();
        $usageRatio = $activePromotions > 0
            ? round(min(100, ($totalPromotionUsage / max(1, $activePromotions)) * 100), 1)
            : 0;

        $usersCount = User::count();
        $totalSales = Sale::where('status', 'active')->count();

        return view('admin.index', compact(
            'summary',
            'salesTrend',
            'topItems',
            'recentSales',
            'promotionUsage',
            'activePromotions',
            'usageRatio',
            'usersCount',
            'totalSales',
            'totalPromotionUsage'
        ));
    }
}
