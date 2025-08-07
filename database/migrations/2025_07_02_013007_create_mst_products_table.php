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
        Schema::create('mst_products', function (Blueprint $table) {
            $table->string('product_id')->primary();
            $table->string('product_name')->unique();
            $table->text('product_image')->nullable();
            $table->float('product_price');

            // ? Status Products in Storage
            $table->enum('is_sales', ['stop_sales', 'in_storage','out_of_stock'])->default('in_storage');
            $table->text('product_description')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mst_products');
    }
};
