<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('produks', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('nama_produk');
        });

        // Generate slug untuk produk yang sudah ada
        \App\Models\Produk::all()->each(function ($produk) {
            $base = Str::slug($produk->nama_produk);
            $suffix = substr(md5($produk->id . $produk->created_at), 0, 8);
            $produk->slug = $base . '-' . $suffix;
            $produk->save();
        });
    }

    public function down(): void
    {
        Schema::table('produks', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
