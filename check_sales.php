<?php
require __DIR__ . '/vendor/autoload.php';
use Illuminate\Support\Carbon;
use App\Models\Sale;

$now = Carbon::now();
$start = $now->copy()->startOfDay()->setTimezone('UTC');
$end = $now->copy()->endOfDay()->setTimezone('UTC');

echo Sale::where('status', 'active')->whereBetween('sale_date', [$start, $end])->count();
