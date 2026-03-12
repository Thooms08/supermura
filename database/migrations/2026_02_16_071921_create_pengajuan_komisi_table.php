<?php

// Database/Migrations/xxxx_xx_xx_create_pengajuan_komisis_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('pengajuan_komisi', function (Blueprint $table) {
            $table->id();
            // Merujuk ke tabel affiliators, bukan users
            $table->foreignId('affiliator_id')->constrained('affiliators')->onDelete('cascade');
            $table->decimal('nominal', 15, 2);
            $table->string('nomor_pembayaran');
            $table->enum('status', ['pending', 'success', 'fail'])->default('pending');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('pengajuan_komisi');
    }
};
