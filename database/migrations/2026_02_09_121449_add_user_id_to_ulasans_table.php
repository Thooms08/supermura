<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ulasans', function (Blueprint $table) {
            // Menambahkan user_id setelah kolom ID
            // constrained('users') otomatis merujuk ke tabel users
            // onDelete('cascade') artinya jika user dihapus, ulasannya ikut terhapus
            $table->foreignId('user_id')->after('id')->constrained('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('ulasans', function (Blueprint $table) {
            // Menghapus foreign key dan kolomnya jika migrasi di-rollback
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};