<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            // Tandai pesanan yang dibatalkan sebelum pembayaran (belum ada uang masuk)
            $table->boolean('cancelled_before_payment')->default(false)->after('status');
            $table->timestamp('seen_at')->nullable()->after('cancelled_before_payment');
        });
    }

    public function down(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropColumn(['cancelled_before_payment', 'seen_at']);
        });
    }
};
