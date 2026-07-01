<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('coa', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $t->string('kode', 30);
            $t->string('nama');
            $t->string('tipe'); // aset|kewajiban|ekuitas|pendapatan|beban|kontra
            $t->string('kelompok')->nullable(); // aset_lancar|aset_tetap|...
            $t->string('saldo_normal', 10); // debit|kredit
            $t->foreignId('parent_id')->nullable()->constrained('coa')->nullOnDelete();
            $t->boolean('is_kas')->default(false);
            $t->boolean('is_bank')->default(false);
            $t->boolean('is_postable')->default(true);
            $t->boolean('is_aktif')->default(true);
            $t->bigInteger('saldo_awal')->default(0);
            $t->json('meta')->nullable();
            $t->softDeletes();
            $t->timestamps();
            $t->unique(['tenant_id', 'kode']);
            $t->index(['tenant_id', 'tipe']);
        });

        Schema::create('jurnal', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $t->foreignId('cabang_id')->nullable()->constrained('cabang')->nullOnDelete();
            $t->string('nomor', 30)->unique();
            $t->date('tanggal');
            $t->string('tipe')->default('umum'); // umum|otomatis|penyesuaian|penutup|balik
            $t->string('referensi_type')->nullable(); // morphable: simpanan_transaksi/pinjaman/dll
            $t->unsignedBigInteger('referensi_id')->nullable();
            $t->text('keterangan');
            $t->bigInteger('total_debit')->default(0);
            $t->bigInteger('total_kredit')->default(0);
            $t->boolean('is_posted')->default(false);
            $t->timestamp('posted_at')->nullable();
            $t->foreignId('posted_by')->nullable()->constrained('users')->nullOnDelete();
            $t->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $t->json('meta')->nullable();
            $t->softDeletes();
            $t->timestamps();
            $t->index(['tenant_id', 'tanggal']);
            $t->index(['referensi_type', 'referensi_id']);
        });

        Schema::create('jurnal_detail', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $t->foreignId('jurnal_id')->constrained('jurnal')->cascadeOnDelete();
            $t->foreignId('coa_id')->constrained('coa');
            $t->bigInteger('debit')->default(0);
            $t->bigInteger('kredit')->default(0);
            $t->text('keterangan')->nullable();
            $t->timestamps();
            $t->index(['tenant_id', 'coa_id']);
        });

        Schema::create('periode_akuntansi', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $t->integer('tahun');
            $t->integer('bulan');
            $t->date('tanggal_mulai');
            $t->date('tanggal_akhir');
            $t->string('status')->default('open'); // open|closed
            $t->timestamp('closed_at')->nullable();
            $t->foreignId('closed_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamps();
            $t->unique(['tenant_id', 'tahun', 'bulan']);
        });

        Schema::create('anggaran', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $t->integer('tahun');
            $t->foreignId('coa_id')->constrained('coa');
            $t->bigInteger('jan')->default(0);
            $t->bigInteger('feb')->default(0);
            $t->bigInteger('mar')->default(0);
            $t->bigInteger('apr')->default(0);
            $t->bigInteger('mei')->default(0);
            $t->bigInteger('jun')->default(0);
            $t->bigInteger('jul')->default(0);
            $t->bigInteger('agu')->default(0);
            $t->bigInteger('sep')->default(0);
            $t->bigInteger('okt')->default(0);
            $t->bigInteger('nov')->default(0);
            $t->bigInteger('des')->default(0);
            $t->timestamps();
            $t->unique(['tenant_id', 'tahun', 'coa_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anggaran');
        Schema::dropIfExists('periode_akuntansi');
        Schema::dropIfExists('jurnal_detail');
        Schema::dropIfExists('jurnal');
        Schema::dropIfExists('coa');
    }
};
