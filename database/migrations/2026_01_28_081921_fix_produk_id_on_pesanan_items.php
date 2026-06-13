<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Migration ini sengaja dikosongkan karena foreign key produk_id pada pesanan_items
// sudah dibuat dengan benar (merujuk ke 'produks') di migrasi create_pesanans_table.
return new class extends Migration
{
    public function up(): void
    {
        // No-op: foreign key sudah benar sejak create_pesanans_table
    }

    public function down(): void
    {
        // No-op
    }
};