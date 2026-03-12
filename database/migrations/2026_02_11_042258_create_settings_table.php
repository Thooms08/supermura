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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            
            // 'key' sebagai pengenal unik (contoh: 'biaya_pendaftaran')
            $table->string('key')->unique();
            
            // 'value' untuk menyimpan nilainya (contoh: '50000')
            // Menggunakan tipe text agar bisa menampung data yang panjang jika diperlukan
            $table->text('value')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};