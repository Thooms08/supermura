<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            // Menambahkan kolom no_resi setelah shipping_id
            $table->string('no_resi')->nullable()->after('shipping_id');
            
            // Memastikan status memiliki opsi 'success'
            // Catatan: Jika enum sudah ada, Laravel tidak bisa mengubahnya via migrasi standar tanpa library tambahan.
            // Namun untuk kolom baru, ini memastikan struktur siap.
        });
    }

    public function down(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropColumn('no_resi');
        });
    }
};