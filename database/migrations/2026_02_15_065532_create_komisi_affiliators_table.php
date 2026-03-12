<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('komisi_affiliators', function (Blueprint $table) {
            $table->id();
            // Foreign key ke produk
            $table->foreignId('produk_id')->constrained('produks')->onDelete('cascade');
            // Nullable karena jika produk satuan, variant_id tidak ada
            $table->foreignId('produk_variant_id')->nullable()->constrained('produk_variants')->onDelete('cascade');
            // Menggunakan decimal untuk akurasi nominal uang
            $table->decimal('nominal_komisi', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('komisi_affiliators');
    }
};