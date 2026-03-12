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
        Schema::table('affiliators', function (Blueprint $table) {
            // Menambah kolom komisi_rekrut dengan tipe decimal (15 digit, 2 desimal)
            // Nullable agar tidak error pada data lama yang sudah ada
            $table->decimal('komisi_rekrut', 15, 2)
                  ->nullable()
                  ->after('nominal_tunai'); // Diletakkan setelah kolom nominal_tunai
        });
    }

    /**
     * Batalkan migrasi (rollback).
     */
    public function down(): void
    {
        Schema::table('affiliators', function (Blueprint $table) {
            // Menghapus kolom komisi_rekrut jika di-rollback
            $table->dropColumn('komisi_rekrut');
        });
    }
};