<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pinjaman_pembayaran', function (Blueprint $t) {
            $t->string('status')->default('pending')->after('metode_bayar');
            $t->string('bukti_bayar')->nullable()->after('status');
            $t->foreignId('verified_by')->nullable()->after('bukti_bayar')->constrained('users')->nullOnDelete();
            $t->timestamp('verified_at')->nullable()->after('verified_by');
            $t->text('catatan_verifikasi')->nullable()->after('verified_at');
        });
    }

    public function down(): void
    {
        Schema::table('pinjaman_pembayaran', function (Blueprint $t) {
            $t->dropColumn(['catatan_verifikasi', 'verified_at', 'verified_by', 'bukti_bayar', 'status']);
        });
    }
};
