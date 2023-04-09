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
        Schema::create('baskets_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('basket_id')->index();
            $table->foreignId('vendor_id')->index();
            $table->foreignId('product_id')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('baskets_products');
    }
};
