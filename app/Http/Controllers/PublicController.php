<?php

namespace App\Http\Controllers;

use App\Models\CryptoPrice;
use Illuminate\Http\Request;

final class PublicController extends Controller
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

        return view('public.crypto', compact('cryptos', 'coins'));
    }
}

