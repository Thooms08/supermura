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
    Schema::create('carts', function (Blueprint $table) {
        $table->id();
        // Menghubungkan ke tabel users
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        // Menghubungkan ke tabel produks
        $table->foreignId('produk_id')->constrained('produks')->onDelete('cascade');
        // Menghubungkan ke tabel produk_variants (nullable karena tidak semua produk punya varian)
        $table->foreignId('variant_id')->nullable()->constrained('produk_variants')->onDelete('cascade');
        $table->integer('qty')->default(1);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
