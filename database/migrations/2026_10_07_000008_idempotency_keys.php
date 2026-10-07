<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Idempotency offline-ready: client_reference unik per tabel transaksi. */
    public function up(): void
    {
        foreach (['simpanan_transaksi', 'pinjaman_pembayaran'] as $table) {
            if (Schema::hasTable($table) && ! Schema::hasColumn($table, 'idempotency_key')) {
                Schema::table($table, function (Blueprint $t) {
                    $t->string('idempotency_key', 80)->nullable()->after('nomor');
                    $t->string('device_id', 60)->nullable()->after('idempotency_key');
                    $t->unique('idempotency_key', $t->getTable().'_idem_unique');
                });
            }
        }
    }

    public function down(): void
    {
        foreach (['simpanan_transaksi', 'pinjaman_pembayaran'] as $table) {
            if (Schema::hasColumn($table, 'idempotency_key')) {
                Schema::table($table, function (Blueprint $t) use ($table) {
                    $t->dropUnique($table.'_idem_unique');
                    $t->dropColumn(['idempotency_key', 'device_id']);
                });
            }
        }
    }
};
