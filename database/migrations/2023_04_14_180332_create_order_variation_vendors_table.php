<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('order_variation_vendors', function (Blueprint $table) {
            $table->id('order_variation_vendor_id');
            $table->foreignId('order_id')->index();
            $table->foreignId('variation_vendor_id')->index();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_variation_vendors');
    }
};
