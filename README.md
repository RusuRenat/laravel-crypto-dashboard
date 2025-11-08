# Cryptocurrency Price Tracker

A Laravel-based application that fetches, stores, and visualizes cryptocurrency prices from the CoinGecko API. Features include real-time data synchronization, interactive charts, and advanced filtering capabilities.

## Features

-   🔐 **Authentication System** - Laravel Breeze for secure user authentication
-   📊 **Interactive Charts** - ApexCharts integration with bar, pie, and line charts
-   🔄 **Automated Sync** - Scheduled cryptocurrency price synchronization via artisan command
-   🔍 **Advanced Filtering** - Filter data by date range, coin, and symbol
-   📱 **Responsive Design** - Modern UI with Tailwind CSS and dark mode support
-   📈 **Real-time Data** - Fetches top cryptocurrency data from CoinGecko API
-   📄 **Pagination** - Efficient data browsing with Laravel pagination

## Tech Stack

-   **Framework:** Laravel 12.x
-   **PHP:** 8.3+
-   **Database:** MySQL / MariaDB / Percona
-   **Frontend:** Blade Templates, Tailwind CSS, Alpine.js
-   **Charts:** ApexCharts
-   **API:** CoinGecko API v3

## Project Structure

```
laravel-test-task/
├── app/
│   ├── Console/
│   │   └── Commands/
│   │       └── SyncCryptoPrices.php      # Artisan command for API sync
│   ├── Http/
│   │   └── Controllers/
│   │       └── DashboardController.php    # Main dashboard controller
│   ├── Models/
│   │   └── CryptoPrice.php                # Cryptocurrency model
│   └── Services/
│       └── CoinGeckoService.php           # CoinGecko API service
├── database/
│   └── migrations/
│       └── *_create_crypto_prices_table.php
├── resources/
│   └── views/
│       ├── dashboard.blade.php            # Data listing page
│       ├── charts.blade.php               # Charts & analytics page
│       └── layouts/
├── routes/
│   ├── web.php                            # Web routes
│   └── console.php                        # Scheduler configuration
└── README.md
```

## Installation & Setup

### Prerequisites

-   PHP 8.3 or higher
-   Composer
-   Node.js & NPM
-   MySQL/MariaDB/Percona
-   Web server (Apache/Nginx)

### Step 1: Clone the Repository

```bash
git clone https://github.com/RusuRenat/laravel-crypto-dashboard laravel-crypto-dashboard
cd laravel-crypto-dashboard
```

### Step 2: Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

### Step 3: Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 4: Database Configuration

Edit the `.env` file with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_test_db
DB_USERNAME=root
DB_PASSWORD=root
```

Create the database:

```bash
mysql -u root -p
CREATE DATABASE laravel_test_db;
exit;
```

### Step 5: Run Migrations

```bash
php artisan migrate
```

### Step 6: Build Frontend Assets

```bash
npm run build
# or for development with hot reload
npm run dev
```

### Step 7: Initial Data Sync

Fetch cryptocurrency data from CoinGecko API:

```bash
php artisan crypto:sync --limit=50
```

Options:

-   `--limit=N` - Number of cryptocurrencies to sync (default: 50)

### Step 8: Create User Account

```bash
php artisan tinker
```

Then in the tinker console:

```php
use App\Models\User;
User::create([
    'name' => 'Admin User',
    'email' => 'admin@example.com',
    'password' => bcrypt('password123'),
    'email_verified_at' => now()
]);
exit;
```

### Step 9: Start the Application

```bash
php artisan serve
```

Visit: `http://localhost:8000`

## Usage

### Manual Data Synchronization

Sync cryptocurrency prices manually:

```bash
# Sync top 50 cryptocurrencies (default)
php artisan crypto:sync

# Sync top 20 cryptocurrencies
php artisan crypto:sync --limit=20

# Sync top 100 cryptocurrencies
php artisan crypto:sync --limit=100
```

### Automated Synchronization (Scheduler)

The application is configured to automatically sync data every hour. To enable the scheduler, add this cron entry:

```bash
# Edit crontab
crontab -e

# Add this line
* * * * * cd laravel-crypto-dashboard && php artisan schedule:run >> /dev/null 2>&1
```

Or run the scheduler manually for testing:

```bash
php artisan schedule:work
```

### Application Features

1. **Login** - Navigate to `/login` and use your credentials
2. **Data Page** - View paginated cryptocurrency data with filters
3. **Charts Page** - Visualize data with interactive charts
4. **Filters Available:**
    - Date range (from/to)
    - Specific cryptocurrency
    - Symbol search

## API Information

### CoinGecko API

-   **Endpoint:** `https://api.coingecko.com/api/v3`
-   **Documentation:** https://docs.coingecko.com/
-   **Rate Limits:** Free tier allows 10-30 requests/minute
-   **No API Key Required** for basic usage

### Data Fields Stored

-   `coin_id` - Unique cryptocurrency identifier
-   `symbol` - Trading symbol (e.g., BTC, ETH)
-   `name` - Full name (e.g., Bitcoin, Ethereum)
-   `current_price` - Current price in USD
-   `market_cap` - Total market capitalization
-   `total_volume` - 24h trading volume
-   `price_change_24h` - Absolute price change (24h)
-   `price_change_percentage_24h` - Percentage change (24h)
-   `circulating_supply` - Circulating supply
-   `total_supply` - Total supply
-   `market_cap_rank` - Market cap ranking
-   `created_at` / `updated_at` - Timestamps

## Charts & Visualizations

### Bar Chart

-   **Title:** Top 10 by Market Cap
-   **Data:** Market capitalization in billions USD
-   **Purpose:** Compare market dominance

### Pie Chart

-   **Title:** Volume Distribution
-   **Data:** 24h trading volume in millions USD
-   **Purpose:** Visualize trading activity distribution

### Line Chart

-   **Title:** 24h Price Change Percentage
-   **Data:** Price change percentage over 24 hours
-   **Purpose:** Identify gainers and losers

## Database Schema

### `crypto_prices` Table

| Column                      | Type          | Description           |
| --------------------------- | ------------- | --------------------- |
| id                          | bigint        | Primary key           |
| coin_id                     | varchar       | Cryptocurrency ID     |
| symbol                      | varchar       | Trading symbol        |
| name                        | varchar       | Full name             |
| current_price               | decimal(20,8) | Current USD price     |
| market_cap                  | bigint        | Market capitalization |
| total_volume                | bigint        | 24h volume            |
| price_change_24h            | decimal(20,8) | 24h price change      |
| price_change_percentage_24h | decimal(10,2) | 24h % change          |
| circulating_supply          | bigint        | Circulating supply    |
| total_supply                | bigint        | Total supply          |
| market_cap_rank             | int           | Market rank           |
| created_at                  | timestamp     | Record timestamp      |
| updated_at                  | timestamp     | Update timestamp      |

**Indexes:**

-   `coin_id` - For filtering by cryptocurrency
-   `created_at` - For date range queries

## Code Architecture

### Design Patterns & Best Practices

-   **Service Pattern** - `CoinGeckoService` handles external API communication
-   **Repository Pattern** - Eloquent ORM for database operations
-   **SOLID Principles** - Final classes, single responsibility
-   **Type Safety** - Strict PHP typing with return type declarations
-   **Error Handling** - Try-catch blocks with comprehensive logging
-   **PSR-12** - Follows PHP coding standards

### Controllers

**DashboardController** (Final class, read-only)

-   `index()` - Data listing with filters and pagination
-   `charts()` - Chart visualizations and datatable

### Services

**CoinGeckoService** (Final class)

-   `getTopCryptos()` - Fetch top N cryptocurrencies
-   `getCryptoById()` - Fetch specific cryptocurrency
-   `transformCryptoData()` - Transform API response

### Commands

**SyncCryptoPrices**

-   Signature: `crypto:sync {--limit=50}`
-   Fetches data from CoinGecko
-   Progress bar for visual feedback
-   Error handling and logging

## Troubleshooting

### Database Connection Issues

```bash
# Test database connection
php artisan tinker
DB::connection()->getPdo();
```

### Migration Errors

```bash
# Fresh migration
php artisan migrate:fresh

# Rollback and re-run
php artisan migrate:rollback
php artisan migrate
```

### API Request Failures

Check logs:

```bash
tail -f storage/logs/laravel.log
```

Common issues:

-   Rate limiting (wait 60 seconds)
-   Network connectivity
-   API endpoint changes

### Build Assets Failed

```bash
# Clear cache and rebuild
rm -rf node_modules package-lock.json
npm install
npm run build
```

## Development

### Run in Development Mode

```bash
# Terminal 1 - Laravel dev server
php artisan serve

# Terminal 2 - Vite dev server (hot reload)
npm run dev
```

### Testing the Sync Command

```bash
# Verbose output
php artisan crypto:sync --limit=5 -vvv
```

### Clear Caches

```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

## Production Deployment

1. Set environment to production:

```env
APP_ENV=production
APP_DEBUG=false
```

2. Optimize the application:

```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build
```

3. Set proper permissions:

```bash
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

4. Configure web server (Nginx/Apache)
5. Set up SSL certificate
6. Enable scheduler cron job
7. Configure queue workers if using queues

## Security Considerations

-   ✅ CSRF protection enabled
-   ✅ XSS prevention via Blade escaping
-   ✅ SQL injection prevention via Eloquent
-   ✅ Authentication required for all routes
-   ✅ Environment variables for sensitive data
-   ✅ Input validation and sanitization

## License

This project is open-source and available under the MIT License.

## Support

For issues and questions:

-   Check Laravel documentation: https://laravel.com/docs
-   CoinGecko API docs: https://docs.coingecko.com/
-   Laravel community: https://laracasts.com/

## Credits

-   **Laravel Framework** - Taylor Otwell
-   **CoinGecko API** - Free cryptocurrency API
-   **ApexCharts** - Modern charting library
-   **Tailwind CSS** - Utility-first CSS framework

---

**Built with ❤️ using Laravel 12 and modern web technologies**
