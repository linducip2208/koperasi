<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('produk_simpanan', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $t->string('kode', 20);
            $t->string('nama');
            $t->string('jenis'); // pokok|wajib|sukarela|berjangka|khusus
            $t->string('akad_type')->default('konvensional'); // konvensional|wadiah|mudharabah
            $t->bigInteger('setoran_minimum')->default(0);
            $t->bigInteger('setoran_wajib')->default(0); // untuk simpanan wajib bulanan
            $t->bigInteger('saldo_minimum')->default(0);
            $t->decimal('bunga_persen_tahun', 8, 4)->default(0);
            $t->string('metode_bunga')->nullable(); // saldo_harian|saldo_rata2|saldo_akhir
            $t->decimal('nisbah_anggota', 5, 2)->default(0); // % syariah
            $t->decimal('nisbah_koperasi', 5, 2)->default(0); // % syariah
            $t->boolean('boleh_tarik')->default(true);
            $t->boolean('berjangka')->default(false);
            $t->integer('tenor_bulan')->nullable();
            $t->boolean('auto_potong_shu')->default(false);
            $t->foreignId('coa_simpanan_id')->nullable()->constrained('coa');
            $t->foreignId('coa_bunga_id')->nullable()->constrained('coa');
            $t->boolean('aktif')->default(true);
            $t->json('meta')->nullable();
            $t->softDeletes();
            $t->timestamps();
            $t->unique(['tenant_id', 'kode']);
        });

        Schema::create('simpanan', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $t->foreignId('cabang_id')->nullable()->constrained('cabang')->nullOnDelete();
            $t->foreignId('anggota_id')->constrained('anggota');
            $t->foreignId('produk_id')->constrained('produk_simpanan');
            $t->string('nomor_rekening', 30)->unique();
            $t->bigInteger('saldo')->default(0);
            $t->bigInteger('saldo_blokir')->default(0);
            $t->bigInteger('setoran_pokok')->default(0); // untuk berjangka
            $t->date('tanggal_buka');
            $t->date('tanggal_jatuh_tempo')->nullable();
            $t->date('tanggal_tutup')->nullable();
            $t->string('status')->default('aktif'); // aktif|tutup|blokir|jatuh_tempo
            $t->json('meta')->nullable();
            $t->softDeletes();
            $t->timestamps();
            $t->index(['tenant_id', 'anggota_id']);
            $t->index(['tenant_id', 'status']);
        });

        Schema::create('simpanan_transaksi', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $t->foreignId('simpanan_id')->constrained('simpanan');
            $t->string('nomor', 30)->unique();
            $t->date('tanggal');
            $t->string('jenis'); // setor|tarik|bunga|bagi_hasil|pajak|denda|admin|blokir|unblok|tutup
            $t->bigInteger('jumlah');
            $t->bigInteger('saldo_sebelum');
            $t->bigInteger('saldo_sesudah');
            $t->foreignId('kas_id')->nullable()->constrained('kas');
            $t->string('metode_bayar')->nullable(); // cash|transfer|potong_gaji|auto_debet
            $t->text('keterangan')->nullable();
            $t->foreignId('jurnal_id')->nullable()->constrained('jurnal')->nullOnDelete();
            $t->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamps();
            $t->index(['tenant_id', 'tanggal']);
        });

        Schema::create('simpanan_blokir', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $t->foreignId('simpanan_id')->constrained('simpanan');
            $t->bigInteger('jumlah');
            $t->string('alasan'); // jaminan_pinjaman|sengketa|lain
            $t->string('referensi_type')->nullable();
            $t->unsignedBigInteger('referensi_id')->nullable();
            $t->date('tanggal_blokir');
            $t->date('tanggal_lepas')->nullable();
            $t->boolean('aktif')->default(true);
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('simpanan_blokir');
        Schema::dropIfExists('simpanan_transaksi');
        Schema::dropIfExists('simpanan');
        Schema::dropIfExists('produk_simpanan');
    }
};
