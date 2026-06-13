<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            // Timestamp ketika admin menandai pesanan sebagai "Dikirim"
            // Digunakan untuk menghitung 3 hari sebelum tombol "Paket Sudah Diterima" aktif
            $table->timestamp('sent_at')->nullable()->after('no_resi');
        });
    }

    public function down(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropColumn('sent_at');
        });
    }
};
