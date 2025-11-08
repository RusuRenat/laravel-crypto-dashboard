<?php

namespace App\Http\Controllers;

use App\Models\CryptoPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

final class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $query = CryptoPrice::query();

        // Date filter
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }

        // Coin filter
        if ($request->filled('coin_id')) {
            $query->where('coin_id', $request->coin_id);
        }

        // Symbol filter
        if ($request->filled('symbol')) {
            $query->where('symbol', 'like', '%' . $request->symbol . '%');
        }

        $cryptos = $query->orderBy('created_at', 'desc')->paginate(50);

        // Get unique coins for filter dropdown
        $coins = CryptoPrice::select('coin_id', 'name')
            ->distinct()
            ->orderBy('name')
            ->get();

        return view('dashboard', compact('cryptos', 'coins'));
    }

    public function charts(Request $request)
    {
        // Get the latest record for each coin
        $latestPrices = CryptoPrice::select('coin_id', DB::raw('MAX(id) as max_id'))
            ->groupBy('coin_id')
            ->get()
            ->pluck('max_id');

        $cryptos = CryptoPrice::whereIn('id', $latestPrices)
            ->orderBy('market_cap_rank')
            ->limit(10)
            ->get();

        // Bar chart data - Top 10 by Market Cap
        $barChartData = [
            'labels' => $cryptos->pluck('symbol')->toArray(),
            'data' => $cryptos->pluck('market_cap')->map(fn($val) => $val / 1000000000)->toArray(), // Convert to billions
        ];

        // Pie chart data - Top 10 by Volume
        $pieChartData = [
            'labels' => $cryptos->pluck('symbol')->toArray(),
            'data' => $cryptos->pluck('total_volume')->map(fn($val) => $val / 1000000)->toArray(), // Convert to millions
        ];

        // Line chart data - Price changes 24h
        $lineChartData = [
            'labels' => $cryptos->pluck('symbol')->toArray(),
            'data' => $cryptos->pluck('price_change_percentage_24h')->toArray(),
        ];

        // Apply filters for datatable
        $query = CryptoPrice::query();

        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }

        if ($request->filled('coin_id')) {
            $query->where('coin_id', $request->coin_id);
        }

        $tableData = $query->orderBy('created_at', 'desc')->paginate(50);

        // Get unique coins for filter
        $coins = CryptoPrice::select('coin_id', 'name')
            ->distinct()
            ->orderBy('name')
            ->get();

        return view('charts', compact('barChartData', 'pieChartData', 'lineChartData', 'tableData', 'coins'));
    }
}
