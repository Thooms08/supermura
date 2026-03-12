<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pesanan_items', function (Blueprint $table) {
            // Menambah kolom setelah produk_id
            $table->unsignedBigInteger('produk_variant_id')->nullable()->after('produk_id');
            
            // Opsional: Tambahkan foreign key jika tabel produk_variants sudah ada
            $table->foreign('produk_variant_id')
                  ->references('id')
                  ->on('produk_variants')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('pesanan_items', function (Blueprint $table) {
            $table->dropForeign(['produk_variant_id']);
            $table->dropColumn('produk_variant_id');
        });
    }
};