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
        Schema::table('produks', function (Blueprint $table) {
            // Menambahkan kolom toko_id setelah kolom id
            // constrained('tokos') otomatis merujuk ke tabel tokos
            // onDelete('cascade') artinya jika toko dihapus, produknya juga ikut terhapus
            $table->foreignId('toko_id')
                  ->after('id')
                  ->constrained('tokos')
                  ->onDelete('cascade');
        });
    }

    /**
     * Batalkan migrasi (Rollback).
     */
    public function down(): void
    {
        Schema::table('produks', function (Blueprint $table) {
            // Hapus constraint foreign key terlebih dahulu sebelum menghapus kolomnya
            $table->dropForeign(['toko_id']);
            $table->dropColumn('toko_id');
        });
    }
};