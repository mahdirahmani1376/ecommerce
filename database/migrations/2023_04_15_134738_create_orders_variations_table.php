<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('orders_variations', function (Blueprint $table) {
            $table->id('order_variation_id');
            $table->foreignId('order_id');
            $table->foreignId('basket_variation_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders_baskets_variations');
    }
};
