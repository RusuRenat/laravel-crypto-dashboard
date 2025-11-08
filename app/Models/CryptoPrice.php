<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class CryptoPrice extends Model
{
    protected $fillable = [
        'coin_id',
        'symbol',
        'name',
        'current_price',
        'market_cap',
        'total_volume',
        'price_change_24h',
        'price_change_percentage_24h',
        'circulating_supply',
        'total_supply',
        'market_cap_rank',
    ];

    protected $casts = [
        'current_price' => 'decimal:8',
        'market_cap' => 'integer',
        'total_volume' => 'integer',
        'price_change_24h' => 'decimal:8',
        'price_change_percentage_24h' => 'decimal:2',
        'circulating_supply' => 'integer',
        'total_supply' => 'integer',
        'market_cap_rank' => 'integer',
    ];
}
