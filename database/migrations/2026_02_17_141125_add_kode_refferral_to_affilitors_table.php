<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan perintah untuk menambah kolom.
     */
    public function up(): void
    {
        Schema::table('affiliators', function (Blueprint $table) {
            // Menambahkan kolom kode_referral (string 8, nullable, unique) 
            // diletakkan setelah kolom id_unik
            $table->string('kode_referral', 8)
                  ->nullable()
                  ->unique()
                  ->after('id_unik');
        });
    }

    /**
     * Jalankan perintah untuk menghapus kolom (rollback).
     */
    public function down(): void
    {
        Schema::table('affiliators', function (Blueprint $table) {
            // Menghapus kolom kode_referral jika migration di-rollback
            $table->dropColumn('kode_referral');
        });
    }
};