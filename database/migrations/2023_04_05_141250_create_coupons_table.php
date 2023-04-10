<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id('coupon_id');
            $table->integer('discount_percent')->nullable();
            $table->integer('discount_price')->nullable();
            $table->integer('max_discount')->nullable();
            $table->integer('min_basket_limit')->nullable();
            $table->boolean('used')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('coupons');
    }
};
