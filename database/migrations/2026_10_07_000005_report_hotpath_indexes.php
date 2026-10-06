<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Index jalur panas report (idempotent — cek dulu). */
    public function up(): void
    {
        $this->addIndex('simpanan_transaksi', 'stx_tanggal_jenis', ['tanggal', 'jenis']);
        $this->addIndex('simpanan_transaksi', 'stx_simpanan_tanggal', ['simpanan_id', 'tanggal']);
        $this->addIndex('pinjaman_jadwal', 'jj_tempo_status', ['tanggal_jatuh_tempo', 'status']);
        $this->addIndex('pinjaman_jadwal', 'jj_pinjaman_status', ['pinjaman_id', 'status']);
        $this->addIndex('pinjaman_pembayaran', 'pb_tanggal_status', ['tanggal', 'status']);
        $this->addIndex('jurnal', 'j_tanggal_posted', ['tanggal', 'is_posted']);
        $this->addIndex('jurnal_detail', 'jd_coa', ['coa_id']);
        $this->addIndex('anggota', 'agt_masuk', ['tanggal_masuk']);
        $this->addIndex('pinjaman', 'pjn_cair', ['tanggal_pencairan']);
    }

    public function down(): void
    {
        foreach ([
            ['simpanan_transaksi', 'stx_tanggal_jenis'],
            ['simpanan_transaksi', 'stx_simpanan_tanggal'],
            ['pinjaman_jadwal', 'jj_tempo_status'],
            ['pinjaman_jadwal', 'jj_pinjaman_status'],
            ['pinjaman_pembayaran', 'pb_tanggal_status'],
            ['jurnal', 'j_tanggal_posted'],
            ['jurnal_detail', 'jd_coa'],
            ['anggota', 'agt_masuk'],
            ['pinjaman', 'pjn_cair'],
        ] as [$table, $index]) {
            try {
                Schema::table($table, fn (Blueprint $t) => $t->dropIndex($index));
            } catch (\Throwable) {
            }
        }
    }

    private function addIndex(string $table, string $index, array $columns): void
    {
        if (! Schema::hasTable($table)) return;
        $exists = collect(Schema::getIndexes($table))->contains(fn ($i) => ($i['name'] ?? '') === $index);
        if (! $exists) {
            Schema::table($table, fn (Blueprint $t) => $t->index($columns, $index));
        }
    }
};
