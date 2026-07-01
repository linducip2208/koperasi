<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('shu_perhitungan', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $t->integer('tahun');
            $t->bigInteger('shu_total'); // SHU sebelum dibagi
            $t->decimal('persen_jasa_modal', 5, 2)->default(0);
            $t->decimal('persen_jasa_anggota', 5, 2)->default(0);
            $t->decimal('persen_dana_cadangan', 5, 2)->default(0);
            $t->decimal('persen_dana_pendidikan', 5, 2)->default(0);
            $t->decimal('persen_dana_sosial', 5, 2)->default(0);
            $t->decimal('persen_dana_pengurus', 5, 2)->default(0);
            $t->decimal('persen_dana_karyawan', 5, 2)->default(0);
            $t->bigInteger('jumlah_jasa_modal')->default(0);
            $t->bigInteger('jumlah_jasa_anggota')->default(0);
            $t->bigInteger('jumlah_dana_cadangan')->default(0);
            $t->bigInteger('jumlah_dana_pendidikan')->default(0);
            $t->bigInteger('jumlah_dana_sosial')->default(0);
            $t->bigInteger('jumlah_dana_pengurus')->default(0);
            $t->bigInteger('jumlah_dana_karyawan')->default(0);
            $t->string('status')->default('draft'); // draft|disetujui|dibagikan
            $t->timestamp('approved_at')->nullable();
            $t->timestamp('distributed_at')->nullable();
            $t->foreignId('jurnal_id')->nullable()->constrained('jurnal')->nullOnDelete();
            $t->json('meta')->nullable();
            $t->timestamps();
            $t->unique(['tenant_id', 'tahun']);
        });

        Schema::create('shu_distribusi', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $t->foreignId('shu_perhitungan_id')->constrained('shu_perhitungan')->cascadeOnDelete();
            $t->foreignId('anggota_id')->constrained('anggota');
            $t->bigInteger('total_simpanan'); // basis perhitungan jasa modal
            $t->bigInteger('total_transaksi'); // basis perhitungan jasa anggota (volume pinjaman, belanja toko, dll)
            $t->bigInteger('jasa_modal');
            $t->bigInteger('jasa_anggota');
            $t->bigInteger('total_shu');
            $t->string('metode_distribusi')->default('simpanan_sukarela'); // simpanan_sukarela|tunai|tahan
            $t->string('status')->default('belum_dibagikan'); // belum_dibagikan|dibagikan
            $t->timestamp('distributed_at')->nullable();
            $t->timestamps();
            $t->index(['tenant_id', 'anggota_id']);
        });

        Schema::create('shu_komponen_setting', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $t->string('nama_komponen');
            $t->string('basis'); // simpanan|pinjaman|belanja|jasa
            $t->decimal('persen', 5, 2);
            $t->boolean('aktif')->default(true);
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shu_komponen_setting');
        Schema::dropIfExists('shu_distribusi');
        Schema::dropIfExists('shu_perhitungan');
    }
};
