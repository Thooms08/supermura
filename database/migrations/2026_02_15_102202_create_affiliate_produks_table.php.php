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
         Schema::create('affiliate_produks', function (Blueprint $table) {
    $table->id();
    $table->foreignId('affiliator_id')->constrained('affiliators')->onDelete('cascade');
    $table->foreignId('produk_id')->constrained('produks')->onDelete('cascade');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
