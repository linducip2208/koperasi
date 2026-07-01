<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kas', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $t->foreignId('cabang_id')->nullable()->constrained('cabang')->nullOnDelete();
            $t->string('kode', 20);
            $t->string('nama');
            $t->string('tipe')->default('kas'); // kas|bank
            $t->string('nomor_rekening')->nullable();
            $t->string('nama_bank')->nullable();
            $t->string('atas_nama')->nullable();
            $t->foreignId('coa_id')->constrained('coa');
            $t->bigInteger('saldo_awal')->default(0);
            $t->bigInteger('saldo')->default(0);
            $t->bigInteger('limit_minimum')->default(0);
            $t->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // kasir penanggung jawab
            $t->boolean('aktif')->default(true);
            $t->softDeletes();
            $t->timestamps();
            $t->unique(['tenant_id', 'kode']);
        });

        Schema::create('kas_transaksi', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $t->foreignId('kas_id')->constrained('kas');
            $t->string('nomor', 30)->unique();
            $t->date('tanggal');
            $t->string('jenis'); // masuk|keluar|transfer_masuk|transfer_keluar|setor_bank|tarik_bank
            $t->bigInteger('jumlah');
            $t->foreignId('coa_id')->nullable()->constrained('coa'); // counter account
            $t->foreignId('kas_tujuan_id')->nullable()->constrained('kas')->nullOnDelete();
            $t->string('referensi_type')->nullable();
            $t->unsignedBigInteger('referensi_id')->nullable();
            $t->text('keterangan');
            $t->foreignId('jurnal_id')->nullable()->constrained('jurnal')->nullOnDelete();
            $t->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $t->softDeletes();
            $t->timestamps();
            $t->index(['tenant_id', 'tanggal']);
        });

        Schema::create('kas_opname', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $t->foreignId('kas_id')->constrained('kas');
            $t->date('tanggal');
            $t->bigInteger('saldo_sistem');
            $t->bigInteger('saldo_fisik');
            $t->bigInteger('selisih');
            $t->text('catatan')->nullable();
            $t->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamps();
        });

        Schema::create('rekonsiliasi_bank', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $t->foreignId('kas_id')->constrained('kas');
            $t->date('tanggal');
            $t->date('periode_akhir');
            $t->bigInteger('saldo_buku');
            $t->bigInteger('saldo_bank');
            $t->bigInteger('selisih');
            $t->json('rincian')->nullable(); // outstanding deposits/checks
            $t->string('status')->default('draft'); // draft|reconciled
            $t->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekonsiliasi_bank');
        Schema::dropIfExists('kas_opname');
        Schema::dropIfExists('kas_transaksi');
        Schema::dropIfExists('kas');
    }
};
