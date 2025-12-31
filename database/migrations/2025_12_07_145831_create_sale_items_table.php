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
        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel sales
            $table->foreignId('sale_id')
                  ->constrained('sales')
                  ->onDelete('cascade');

            // Relasi ke tabel products
            $table->foreignId('product_id')
                  ->constrained('products')
                  ->onDelete('cascade');

            // Jumlah barang
            $table->unsignedInteger('qty');

            // Harga satuan saat transaksi
            $table->unsignedInteger('price');

            // qty * price
            $table->unsignedInteger('subtotal');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_items');
    }
};
