<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loyalty_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('anggota_id')->constrained('anggota');
            $table->bigInteger('poin')->default(0);
            $table->bigInteger('total_poin_diterima')->default(0);
            $table->bigInteger('total_poin_ditukar')->default(0);
            $table->timestamps();
            $table->unique(['tenant_id', 'anggota_id']);
        });

        Schema::create('loyalty_transaksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('anggota_id')->constrained('anggota');
            $table->string('jenis'); // dapat|tukar|kadaluarsa|referral
            $table->bigInteger('jumlah');
            $table->string('keterangan')->nullable();
            $table->string('referensi_type')->nullable();
            $table->unsignedBigInteger('referensi_id')->nullable();
            $table->timestamps();
            $table->index(['tenant_id', 'anggota_id', 'jenis']);
        });

        Schema::create('referral', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('pengajak_id')->constrained('anggota');
            $table->foreignId('diajak_id')->constrained('anggota');
            $table->string('kode_referral', 20)->unique();
            $table->bigInteger('komisi')->default(0);
            $table->string('status')->default('pending'); // pending|terdaftar|aktif|komisi_dibayar
            $table->timestamp('tanggal_daftar')->nullable();
            $table->timestamp('tanggal_aktif')->nullable();
            $table->timestamp('tanggal_komisi')->nullable();
            $table->timestamps();
        });

        Schema::table('anggota', function (Blueprint $table) {
            $table->string('kode_referral', 20)->nullable()->unique()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('anggota', function (Blueprint $table) {
            $table->dropColumn('kode_referral');
        });
        Schema::dropIfExists('referral');
        Schema::dropIfExists('loyalty_transaksi');
        Schema::dropIfExists('loyalty_points');
    }
};
