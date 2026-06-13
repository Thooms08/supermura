<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->text('rekening_pengembalian')->nullable()->after('status');
            $table->text('alasan_pembatalan')->nullable()->after('rekening_pengembalian');
        });
    }

    public function down(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropColumn(['rekening_pengembalian', 'alasan_pembatalan']);
        });
    }

};
