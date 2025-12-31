<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sale_items', function (Blueprint $table) {
            if (!Schema::hasColumn('sale_items', 'cost')) {
                $table->unsignedBigInteger('cost')->default(0);
            }
            if (!Schema::hasColumn('sale_items', 'profit')) {
                $table->bigInteger('profit')->default(0); // signed
            }
        });
    }

    public function down(): void
    {
        Schema::table('sale_items', function (Blueprint $table) {
            if (Schema::hasColumn('sale_items', 'profit')) $table->dropColumn('profit');
            if (Schema::hasColumn('sale_items', 'cost')) $table->dropColumn('cost');
        });
    }
};
