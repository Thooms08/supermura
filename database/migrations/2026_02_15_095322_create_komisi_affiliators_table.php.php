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
        Schema::create('komisi_affiliators_record', function (Blueprint $table) {
    $table->id();
    $table->foreignId('affiliator_id')->constrained('affiliators');
    $table->foreignId('pesanan_id')->constrained('pesanan');
    $table->foreignId('produk_id')->constrained('produks');
    $table->foreignId('produk_variant_id')->nullable()->constrained('produk_variants');
    $table->decimal('nominal_komisi', 15, 2);
    $table->enum('status', ['pending', 'berhasil', 'batal'])->default('pending');
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
