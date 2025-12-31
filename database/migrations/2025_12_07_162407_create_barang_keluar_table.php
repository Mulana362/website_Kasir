<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang_keluar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->integer('qty');
            $table->string('keterangan')->nullable(); // contoh: "Penjualan INV-XXXX"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang_keluar');
    }
};
