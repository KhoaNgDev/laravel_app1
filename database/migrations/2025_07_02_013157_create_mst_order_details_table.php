<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mst_order_details', function (Blueprint $table) {
            $table->unsignedBigInteger('id');
            $table->integer('detail_line');


            $table->integer('price_buy');
            $table->integer('quantity');
            $table->string('shop_id', 50);
            $table->integer('receiver_id');

            $table->string('mst_product_id');
            $table->foreign('mst_product_id')->references('product_id')->on('mst_products');
            $table->primary(['id', 'detail_line']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mst_order_details');
    }
};
