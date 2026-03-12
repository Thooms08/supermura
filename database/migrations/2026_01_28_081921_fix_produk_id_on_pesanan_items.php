<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pesanan_items', function (Blueprint $table) {
            // 1. Hapus foreign key lama yang salah (jika ada)
            // Laravel biasanya memberi nama: namaTabel_namaKolom_foreign
            $table->dropForeign(['produk_id']);

            // 2. Hubungkan kembali ke tabel yang benar ('produks')
            $table->foreign('produk_id')
                  ->references('id')
                  ->on('produks')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('pesanan_items', function (Blueprint $table) {
            $table->dropForeign(['produk_id']);
        });
    }
};