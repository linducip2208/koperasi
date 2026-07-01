<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('anggota', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $t->foreignId('cabang_id')->nullable()->constrained('cabang')->nullOnDelete();
            $t->string('nomor_anggota', 30)->unique();
            $t->string('nik', 20)->nullable()->index();
            $t->string('nama');
            $t->string('tempat_lahir')->nullable();
            $t->date('tanggal_lahir')->nullable();
            $t->string('jenis_kelamin', 1)->nullable(); // L|P
            $t->string('agama')->nullable();
            $t->string('status_perkawinan')->nullable();
            $t->text('alamat')->nullable();
            $t->string('rt', 5)->nullable();
            $t->string('rw', 5)->nullable();
            $t->string('kelurahan')->nullable();
            $t->string('kecamatan')->nullable();
            $t->string('kota')->nullable();
            $t->string('provinsi')->nullable();
            $t->string('kode_pos', 10)->nullable();
            $t->string('telp')->nullable();
            $t->string('email')->nullable();
            $t->string('npwp', 25)->nullable();
            $t->string('pekerjaan')->nullable();
            $t->string('nama_perusahaan')->nullable();
            $t->bigInteger('penghasilan_bulanan')->nullable();
            $t->string('sumber_dana')->nullable();
            $t->string('foto_path')->nullable();
            $t->string('ktp_path')->nullable();
            $t->string('kk_path')->nullable();
            $t->string('kategori')->default('biasa'); // biasa|luar_biasa|calon|kehormatan
            $t->string('status')->default('aktif'); // aktif|tidak_aktif|keluar|meninggal|dikeluarkan
            $t->date('tanggal_masuk')->nullable();
            $t->date('tanggal_keluar')->nullable();
            $t->text('alasan_keluar')->nullable();
            $t->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // login akun anggota
            $t->json('meta')->nullable();
            $t->softDeletes();
            $t->timestamps();
            $t->index(['tenant_id', 'status']);
            $t->index(['tenant_id', 'nama']);
        });

        Schema::create('anggota_status_log', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $t->foreignId('anggota_id')->constrained('anggota')->cascadeOnDelete();
            $t->string('dari_status')->nullable();
            $t->string('ke_status');
            $t->date('tanggal');
            $t->text('catatan')->nullable();
            $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $t->timestamps();
        });

        Schema::create('ahli_waris', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $t->foreignId('anggota_id')->constrained('anggota')->cascadeOnDelete();
            $t->string('nama');
            $t->string('hubungan'); // istri/suami/anak/orang_tua/saudara
            $t->string('nik', 20)->nullable();
            $t->date('tanggal_lahir')->nullable();
            $t->string('telp')->nullable();
            $t->text('alamat')->nullable();
            $t->decimal('persentase', 5, 2)->default(100);
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ahli_waris');
        Schema::dropIfExists('anggota_status_log');
        Schema::dropIfExists('anggota');
    }
};
