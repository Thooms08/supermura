<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            // 1. Tambah kolom shipping_id setelah pengunjung_id
            // Kita set nullable() agar data lama tidak error saat migrasi
            $table->foreignId('shipping_id')
                  ->after('pengunjung_id')
                  ->nullable() 
                  ->constrained('shipping_methods')
                  ->onDelete('set null'); // Jika metode hapus, kolom di pesanan jadi null

            // 2. Opsional: Hapus kolom lama jika sudah tidak diperlukan
            // $table->dropColumn('metode_pengiriman');
        });
    }

    public function down(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropForeign(['shipping_id']);
            $table->dropColumn('shipping_id');
            
            // Jika tadi kolom lama dihapus, di sini harus dikembalikan
            // $table->string('metode_pengiriman')->after('total_harga');
        });
    }
};