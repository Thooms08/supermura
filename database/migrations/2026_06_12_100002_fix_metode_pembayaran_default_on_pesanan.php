<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ubah default value dari 'midtrans' menjadi 'mayar'
        Schema::table('pesanan', function (Blueprint $table) {
            $table->string('metode_pembayaran')->default('mayar')->change();
        });

        // Update data lama yang masih 'midtrans' menjadi 'mayar'
        DB::table('pesanan')->where('metode_pembayaran', 'midtrans')->update([
            'metode_pembayaran' => 'mayar',
        ]);
    }

    public function down(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->string('metode_pembayaran')->default('midtrans')->change();
        });
    }
};
