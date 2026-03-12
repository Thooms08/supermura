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
            $table->foreignId('user_id')
                  ->after('id')
                  ->nullable() 
                  ->constrained('users')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('affiliators', function (Blueprint $table) {
            // Menghapus constraint foreign key terlebih dahulu
            $table->dropForeign(['user_id']);
            // Baru menghapus kolomnya
            $table->dropColumn('user_id');
        });
    }
};