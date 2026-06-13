<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('komisi_affiliators_record', function (Blueprint $table) {
            $table->id();
            $table->foreignId('affiliator_id')->constrained('affiliators')->onDelete('cascade');
            $table->foreignId('pesanan_id')->constrained('pesanan')->onDelete('cascade');
            $table->foreignId('produk_id')->constrained('produks')->onDelete('cascade');
            $table->foreignId('produk_variant_id')->nullable()->constrained('produk_variants')->onDelete('set null');
            $table->decimal('nominal_komisi', 15, 2);
            $table->enum('status', ['pending', 'berhasil', 'batal'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('komisi_affiliators_record');
    }
};
