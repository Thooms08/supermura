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
        Schema::table('affiliators', function (Blueprint $blueprint) {
            // Menambahkan kolom foto_profile setelah kolom nama
            // Menggunakan nullable() agar data lama tidak error
            $blueprint->string('foto_profile')->nullable()->after('nama');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('affiliators', function (Blueprint $blueprint) {
            // Menghapus kolom jika migration di-rollback
            $blueprint->dropColumn('foto_profile');
        });
    }
};