<?php

namespace App\Console\Commands;

use App\Models\CryptoPrice;
use App\Services\CoinGeckoService;
use Illuminate\Console\Command;

final class SyncCryptoPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crypto:sync {--limit=50 : Number of cryptocurrencies to sync}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize cryptocurrency prices from CoinGecko API';

    public function __construct(
        private readonly CoinGeckoService $coinGeckoService
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting cryptocurrency data synchronization...');

        $limit = (int) $this->option('limit');
        
        $cryptos = $this->coinGeckoService->getTopCryptos($limit);

        if (empty($cryptos)) {
            $this->error('Failed to fetch cryptocurrency data from API');
            return self::FAILURE;
        }

        $count = count($cryptos);
        $this->info("Fetched {$count} cryptocurrencies from CoinGecko API");

        $bar = $this->output->createProgressBar($count);
        $bar->start();

        $successCount = 0;
        $errorCount = 0;

        foreach ($cryptos as $crypto) {
            try {
                $data = $this->coinGeckoService->transformCryptoData($crypto);
                
                CryptoPrice::create($data);
                
                $successCount++;
            } catch (\Exception $e) {
                $this->error("\nError processing {$crypto['name']}: {$e->getMessage()}");
                $errorCount++;
            }
            
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("Synchronization completed!");
        $this->info("Success: {$successCount}");
        if ($errorCount > 0) {
            $this->warn("Errors: {$errorCount}");
        }

        return self::SUCCESS;
    }
}
