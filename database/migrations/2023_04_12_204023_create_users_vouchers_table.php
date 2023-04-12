<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users_vouchers', function (Blueprint $table) {
            $table->id('user_voucher_id');
            $table->foreignId('user_id');
            $table->foreignId('voucher_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_vouchers');
    }
};
