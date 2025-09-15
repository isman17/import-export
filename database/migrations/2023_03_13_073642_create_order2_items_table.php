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
        Schema::create('order2_items', function (Blueprint $table) {
            $table->id();
            $table->string('order_code');
            $table->string('parent_sku')->nullable();
            $table->string('name');
            $table->string('sku');
            $table->string('variant_name')->nullable();
            $table->double('regular_price', 15, 10)->nullable();
            $table->double('discount_price', 15, 10)->nullable();
            $table->integer('quantity')->nullable();
            $table->double('total_price', 15, 10)->nullable();
            $table->double('total_discount', 15, 10)->default(0);
            $table->double('seller_discount', 15, 10)->default(0);
            $table->double('shopee_discount', 15, 10)->default(0);
            $table->string('weight')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order2_items');
    }
};
