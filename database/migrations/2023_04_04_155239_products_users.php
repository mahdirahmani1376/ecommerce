<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products_users', function (Blueprint $table) {
            $table->foreignId('product_id')->index();
            $table->foreignId('user_id')->index();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products_users');
    }
};
