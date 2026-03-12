<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('alat_promosi', function (Blueprint $table) {
            $table->id();
            $table->string('poster')->nullable(); // Simpan nama file
            $table->text('caption')->nullable();  // Simpan teks iklan
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('alat_promosi');
    }
};
