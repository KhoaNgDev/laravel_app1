<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mst_customers', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name')->unique();
            $table->string('email')->unique();
            $table->string('tel_num');
            $table->string('address');
            $table->tinyInteger('is_active')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mst_customers');
    }
};
