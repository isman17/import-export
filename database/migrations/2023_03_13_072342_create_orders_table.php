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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('status');
            $table->text('cancel_reason')->nullable();
            $table->string('cancel_status')->nullable();
            $table->string('airwaybill')->nullable();
            $table->string('delivery_service')->nullable();
            $table->string('delivery_pickup')->nullable();
            $table->string('delivery_before')->nullable();
            $table->string('delivery_at')->nullable();
            $table->string('created_at')->nullable();
            $table->string('pay_at')->nullable();
            $table->integer('total_quantity')->nullable();
            $table->string('total_weight')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
