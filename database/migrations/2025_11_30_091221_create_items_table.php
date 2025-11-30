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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('item_name');
            $table->text('description')->nullable();
            $table->string('slug')->unique();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('category_item_id')->nullable();
            $table->unsignedBigInteger('sub_category_item_id')->nullable();
            $table->integer('price');
            $table->string('preview_image')->nullable();
            $table->timestamps();

            // Relations
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('category_item_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('sub_category_item_id')->references('id')->on('sub_categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
