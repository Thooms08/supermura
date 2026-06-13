<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nomor_pesanan')->unique();
            $table->decimal('total_harga', 15, 2);
            $table->string('metode_pengiriman');
            $table->string('metode_pembayaran')->default('midtrans');
            $table->string('snap_token')->nullable();
            $table->enum('status', ['pending', 'success', 'process', 'send', 'fail'])->default('pending');
            $table->timestamps();
        });

        Schema::create('pesanan_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('pesanan_id')->constrained('pesanan')->onDelete('cascade');
    
    // Pastikan ini merujuk ke 'produks' bukan 'produk'
    $table->foreignId('produk_id')->constrained('produks'); 
    
    $table->string('nama_produk');
    $table->integer('qty');
    $table->decimal('harga', 15, 2);
    $table->decimal('subtotal', 15, 2);
    $table->timestamps();
});
    }

    public function down(): void {
        Schema::dropIfExists('pesanan_items');
        Schema::dropIfExists('pesanan');
    }
};