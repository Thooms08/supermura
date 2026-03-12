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
        Schema::table('affiliators', function (Blueprint $table) {
            // Menambahkan kolom id_unik dengan tipe varchar(8) dan index UNIQUE
            // Gunakan nullable() jika sudah ada data lama, atau berikan nilai default
            $table->string('id_unik', 8)->unique()->after('user_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('affiliators', function (Blueprint $table) {
            // Menghapus index unik dan kolom saat rollback
            $table->dropUnique(['id_unik']);
            $table->dropColumn('id_unik');
        });
    }
};