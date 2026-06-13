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
        Schema::create('affiliators', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('no_whatsapp');
            $table->string('domisili');
            $table->enum('metode_pembayaran', ['tunai', 'transfer'])->nullable();
            $table->string('bukti_transfer')->nullable();
            $table->integer('nominal_tunai')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliators');
    }
};
