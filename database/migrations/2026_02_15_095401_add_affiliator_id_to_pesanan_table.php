<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->foreignId('affiliator_id')
                  ->nullable()
                  ->constrained('affiliators')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropForeign(['affiliator_id']);
            $table->dropColumn('affiliator_id');
        });
    }
};
