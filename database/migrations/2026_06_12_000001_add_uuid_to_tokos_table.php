<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tokos', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->unique()->after('id');
        });

        // Isi uuid untuk data yang sudah ada
        \App\Models\Toko::whereNull('uuid')->each(function ($toko) {
            $toko->update(['uuid' => Str::uuid()->toString()]);
        });
    }

    public function down(): void
    {
        Schema::table('tokos', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
