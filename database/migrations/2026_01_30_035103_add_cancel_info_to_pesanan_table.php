<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_add_cancel_info_to_pesanan_table.php
        public function up(): void
        {
            Schema::table('pesanan', function (Blueprint $table) {
                $table->text('rekening_pengembalian')->nullable()->after('status');
                $table->text('alasan_pembatalan')->nullable()->after('rekening_pengembalian');
            });
        }

};
