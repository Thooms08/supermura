<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // Tabel Jenis/Kategori
        Schema::create('kategoris', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jenis')->unique();
            $table->timestamps();
        });

        // Tabel Produk Utama
        Schema::create('produks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('kategoris')->onDelete('cascade');
            $table->string('nama_produk');
            $table->decimal('harga', 15, 2);
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        // Tabel Varian (Size + Model/Warna + Stok)
        Schema::create('produk_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained('produks')->onDelete('cascade');
            $table->string('size'); // S, M, L, XL, dll
            $table->string('model'); // Contoh: Merah, Biru, Motif A
            $table->integer('stok')->default(0);
            $table->timestamps();
        });

        // Tabel Foto Produk (Max 50 per produk)
        Schema::create('produk_fotos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained('produks')->onDelete('cascade');
            $table->string('path_foto');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('produk_fotos');
        Schema::dropIfExists('produk_variants');
        Schema::dropIfExists('produks');
        Schema::dropIfExists('kategoris');
    }
};