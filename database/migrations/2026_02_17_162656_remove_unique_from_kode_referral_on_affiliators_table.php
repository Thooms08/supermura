<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('affiliators', function (Blueprint $table) {
            // Menghapus index unik pada kolom kode_referral
            $table->dropUnique(['kode_referral']);
        });
    }

    public function down(): void
    {
        Schema::table('affiliators', function (Blueprint $table) {
            // Mengembalikan index unik jika di-rollback
            $table->unique('kode_referral');
        });
    }
};