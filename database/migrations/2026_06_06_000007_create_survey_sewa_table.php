<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('survey', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->json('pertanyaan'); // [{teks, tipe: text|rating|pilihan, opsi: []}]
            $table->boolean('is_aktif')->default(false);
            $table->timestamp('mulai')->nullable();
            $table->timestamp('selesai')->nullable();
            $table->timestamps();
        });

        Schema::create('survey_jawaban', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_id')->constrained('survey')->cascadeOnDelete();
            $table->foreignId('anggota_id')->constrained('anggota');
            $table->json('jawaban'); // [{pertanyaan_index, nilai}]
            $table->timestamps();
            $table->unique(['survey_id', 'anggota_id']);
        });

        Schema::create('sewa_aset', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('asset_id')->nullable()->constrained('asset')->nullOnDelete();
            $table->foreignId('anggota_id')->constrained('anggota');
            $table->string('nomor', 30)->unique();
            $table->string('nama_aset');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->bigInteger('tarif_per_hari')->default(0);
            $table->bigInteger('total_harga')->default(0);
            $table->bigInteger('dp')->default(0);
            $table->string('status')->default('aktif'); // aktif, selesai, batal
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('survey_jawaban');
        Schema::dropIfExists('survey');
        Schema::dropIfExists('sewa_aset');
    }
};
