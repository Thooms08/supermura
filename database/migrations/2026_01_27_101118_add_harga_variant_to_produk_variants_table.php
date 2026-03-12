<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::table('produk_variants', function (Blueprint $table) {
            // Menggunakan decimal (15,2) untuk standar harga yang presisi
            // dibuat nullable agar bisa kembali ke harga utama produk jika kosong
            $table->decimal('harga_variant', 15, 2)
                  ->nullable()
                  ->after('model');
        });
    }

    /**
     * Batalkan migrasi (Rollback).
     */
    public function down(): void
    {
        Schema::table('produk_variants', function (Blueprint $table) {
            $table->dropColumn('harga_variant');
        });
    }
};