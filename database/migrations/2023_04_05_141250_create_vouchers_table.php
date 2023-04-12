<?php

use App\Enums\VoucherEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id('voucher_id');
            $table->enum('voucher_enum', VoucherEnum::getAllValues());
            $table->integer('discount_percent')->nullable()->default(0);
            $table->integer('discount_price')->nullable()->default(0);
            $table->integer('max_discount')->nullable()->default(0);
            $table->integer('min_basket_limit')->nullable()->default(0);
            $table->integer('remaining_amount')->nullable();
            $table->boolean('used')->default(false);
            $table->timestamp('expire_date')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('coupons');
    }
};
