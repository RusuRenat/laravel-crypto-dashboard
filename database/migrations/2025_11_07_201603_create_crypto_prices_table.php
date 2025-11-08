<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('crypto_prices', function (Blueprint $table) {
            $table->id();
            $table->string('coin_id');
            $table->string('symbol');
            $table->string('name');
            $table->decimal('current_price', 20, 8);
            $table->bigInteger('market_cap')->nullable();
            $table->bigInteger('total_volume')->nullable();
            $table->decimal('price_change_24h', 20, 8)->nullable();
            $table->decimal('price_change_percentage_24h', 10, 2)->nullable();
            $table->bigInteger('circulating_supply')->nullable();
            $table->bigInteger('total_supply')->nullable();
            $table->integer('market_cap_rank')->nullable();
            $table->timestamps();
            
            $table->index('coin_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crypto_prices');
    }
};
