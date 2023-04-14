<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('baskets_variations', function (Blueprint $table) {
            $table->id('basket_variation_id');
            $table->foreignId('basket_id')->nullable();
            $table->foreignId('variation_vendor_id')->nullable();
            $table->integer('price');
            $table->integer('discount_amount')->nullable();
            $table->integer('discounted_price')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('baskets_variations');
    }
};
