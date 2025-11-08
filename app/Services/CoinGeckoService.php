<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

final class CoinGeckoService
{
    private const API_BASE_URL = 'https://api.coingecko.com/api/v3';
    private const TIMEOUT = 30;

    public function __construct(
        private readonly string $baseUrl = self::API_BASE_URL
    ) {}

    /**
     * Get top cryptocurrencies by market cap
     */
    public function getTopCryptos(int $limit = 10): array
    {
        try {
            $response = Http::timeout(self::TIMEOUT)
                ->get("{$this->baseUrl}/coins/markets", [
                    'vs_currency' => 'usd',
                    'order' => 'market_cap_desc',
                    'per_page' => $limit,
                    'page' => 1,
                    'sparkline' => false,
                    'price_change_percentage' => '24h',
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('CoinGecko API request failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [];
        } catch (\Exception $e) {
            Log::error('CoinGecko API exception', [
                'message' => $e->getMessage(),
            ]);

            return [];
        }
    }

    /**
     * Get specific cryptocurrency data
     */
    public function getCryptoById(string $coinId): ?array
    {
        try {
            $response = Http::timeout(self::TIMEOUT)
                ->get("{$this->baseUrl}/coins/{$coinId}", [
                    'localization' => false,
                    'tickers' => false,
                    'community_data' => false,
                    'developer_data' => false,
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('CoinGecko API request failed for coin', [
                'coin_id' => $coinId,
                'status' => $response->status(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('CoinGecko API exception for coin', [
                'coin_id' => $coinId,
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Transform API response to database-compatible format
     */
    public function transformCryptoData(array $crypto): array
    {
        return [
            'coin_id' => $crypto['id'] ?? '',
            'symbol' => strtoupper($crypto['symbol'] ?? ''),
            'name' => $crypto['name'] ?? '',
            'current_price' => $crypto['current_price'] ?? 0,
            'market_cap' => $crypto['market_cap'] ?? null,
            'total_volume' => $crypto['total_volume'] ?? null,
            'price_change_24h' => $crypto['price_change_24h'] ?? null,
            'price_change_percentage_24h' => $crypto['price_change_percentage_24h'] ?? null,
            'circulating_supply' => $crypto['circulating_supply'] ?? null,
            'total_supply' => $crypto['total_supply'] ?? null,
            'market_cap_rank' => $crypto['market_cap_rank'] ?? null,
        ];
    }
}

