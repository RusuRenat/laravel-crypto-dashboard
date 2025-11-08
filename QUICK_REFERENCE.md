# Quick Reference Card

## 🚀 Start Application

```bash
php artisan serve
# Visit: http://localhost:8000
```

## 👤 Login Credentials

```
Email: admin@test.com
Password: password
```

## 📊 Main Commands

```bash
# Sync crypto data (fetch from API)
php artisan crypto:sync --limit=50

# View scheduled tasks
php artisan schedule:list

# Run scheduler (for testing)
php artisan schedule:work

# Create a new user
php artisan tinker
User::create(['name'=>'John Doe', 'email'=>'john@test.com', 'password'=>bcrypt('password123'), 'email_verified_at'=>now()]);
exit
```

## 🗄️ Database

```bash
# Run migrations
php artisan migrate

# Fresh migration (CAUTION: deletes all data)
php artisan migrate:fresh

# Check database connection
php artisan tinker
DB::connection()->getPdo();
```

## 🎨 Frontend

```bash
# Build for production
npm run build

# Development (hot reload)
npm run dev
```

## 🔧 Maintenance

```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 📍 URLs

- **Login:** http://localhost:8000/login
- **Data Page:** http://localhost:8000/dashboard
- **Charts:** http://localhost:8000/charts
- **Profile:** http://localhost:8000/profile

## 📁 Key Files

| File | Purpose |
|------|---------|
| `app/Services/CoinGeckoService.php` | API integration |
| `app/Console/Commands/SyncCryptoPrices.php` | Sync command |
| `app/Http/Controllers/DashboardController.php` | Main controller |
| `resources/views/dashboard.blade.php` | Data listing |
| `resources/views/charts.blade.php` | Charts page |
| `routes/web.php` | Routes |
| `routes/console.php` | Scheduler config |
| `.env` | Configuration |

## 🔄 Scheduler Setup (Production)

```bash
# Edit crontab
crontab -e

# Add this line
* * * * * cd laravel-crypto-dashboard && php artisan schedule:run >> /dev/null 2>&1
```

## 📊 Data Structure

```sql
crypto_prices table:
- coin_id (varchar)
- symbol (varchar) 
- name (varchar)
- current_price (decimal)
- market_cap (bigint)
- total_volume (bigint)
- price_change_24h (decimal)
- price_change_percentage_24h (decimal)
- circulating_supply (bigint)
- total_supply (bigint)
- market_cap_rank (int)
- created_at, updated_at (timestamps)
```

## 🎯 Quick Tests

```bash
# Test API connection
php artisan tinker
app(\App\Services\CoinGeckoService::class)->getTopCryptos(5);

# Check crypto records count
php artisan tinker
\App\Models\CryptoPrice::count();

# View latest record
php artisan tinker
\App\Models\CryptoPrice::latest()->first();
```

## 📈 Chart Types

1. **Bar Chart** - Top 10 by Market Cap (billions USD)
2. **Pie Chart** - Volume Distribution (millions USD)  
3. **Line Chart** - 24h Price Change Percentage

## 🔍 Available Filters

- **Date From** - Start date for records
- **Date To** - End date for records
- **Coin** - Specific cryptocurrency
- **Symbol** - Search by symbol (e.g., BTC)

## ⚡ Common Issues

| Issue | Solution |
|-------|----------|
| No data showing | Run `php artisan crypto:sync --limit=20` |
| Can't login | Check user exists in database |
| Charts not loading | Check browser console, verify ApexCharts CDN |
| 500 error | Check `storage/logs/laravel.log` |
| CSS not loading | Run `npm run build` |

## 📞 Support Files

- **Full Documentation:** `README.md`
- **Setup Guide:** `SETUP_SUMMARY.md`
- **This File:** `QUICK_REFERENCE.md`

