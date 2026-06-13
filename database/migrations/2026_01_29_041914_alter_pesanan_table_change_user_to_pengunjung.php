<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            // 1. Tambah kolom pengunjung_id sebagai foreign key
            $table->foreignId('pengunjung_id')
                  ->after('id')
                  ->nullable()
                  ->constrained('pengunjung')
                  ->onDelete('cascade');

            // 2. Drop foreign key user_id dulu sebelum drop kolom-nya
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->foreignId('user_id')->after('id')->constrained('users');
            $table->dropForeign(['pengunjung_id']);
            $table->dropColumn('pengunjung_id');
        });
    }
};