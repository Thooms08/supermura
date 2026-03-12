<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('ulasans', function (Blueprint $table) {
        $table->id();
        $table->foreignId('produk_id')->constrained('produks')->onDelete('cascade');
        $table->string('nama');
        $table->integer('rating'); // 1-5
        $table->text('komentar');
        $table->text('foto')->nullable(); // Kita akan simpan sebagai JSON array
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ulasans');
    }
};
