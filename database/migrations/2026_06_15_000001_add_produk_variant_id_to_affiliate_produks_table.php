<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('affiliate_produks', function (Blueprint $table) {
            $table->foreignId('produk_variant_id')
                ->nullable()
                ->after('produk_id')
                ->constrained('produk_variants')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('affiliate_produks', function (Blueprint $table) {
            $table->dropConstrainedForeignId('produk_variant_id');
        });
    }
};