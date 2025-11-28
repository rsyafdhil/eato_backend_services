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
            $table->string('order_id')->unique(); // untuk Midtrans
            $table->unsignedBigInteger('user_id')->nullable(); // kalau ada login
            $table->integer('amount'); // nominal
            $table->string('payment_type')->default('qris');
            $table->string('status')->default('pending');
            // pending | settlement | expire | cancel | deny
            $table->json('raw_response')->nullable(); // simpan response Midtrans (opsional)
            $table->timestamps();
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
