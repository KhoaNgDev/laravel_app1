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
        Schema::create('mst_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_shop');

            // ? Foreign Key
            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('mst_customers')->onDelete('cascade');

            $table->integer('total_price')->default(0);
            $table->enum('payment_method', ['COD', 'PayPal', 'GMO'])->default('COD');
            $table->integer('ship_charge')->nullable();
            $table->integer('tax')->nullable();
            $table->dateTime('order_date');
            $table->dateTime('shipment_date')->nullable();
            $table->dateTime('cancel_date')->nullable();
            $table->tinyInteger('order_status');

            $table->string('note_customer')->nullable();
            $table->string('error_code_api')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mst_orders');
    }
};
