<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('item_name');               // nama item (makanan/minuman)
            $table->text('description')->nullable();     // deskripsi makanan
            $table->string('slug')->unique();          // slug untuk URL
            $table->unsignedBigInteger('category_item_id')->nullable();
            $table->unsignedBigInteger('sub_category_item_id')->nullable();
            $table->integer('price');                  // harga item
            $table->string('preview_image')->nullable(); // URL/path gambar
            $table->timestamps();

            // Kalau kamu punya tabel categories & subcategories, aktifkan foreign keynya:
            // $table->foreign('category_item_id')->references('id')->on('categories')->onDelete('set null');
            // $table->foreign('sub_category_item_id')->references('id')->on('sub_categories')->onDelete('set null');
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
