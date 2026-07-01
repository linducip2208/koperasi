<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ppob_produk', function (Blueprint $table) {
            $table->id();
            $table->string('kategori'); // pulsa, pln, bpjs, ewallet, pdam, internet
            $table->string('kode', 30)->unique();
            $table->string('nama');
            $table->string('nominal', 50)->nullable();
            $table->bigInteger('harga_jual')->default(0);
            $table->bigInteger('harga_beli')->default(0);
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });

        Schema::create('ppob_transaksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('anggota_id')->nullable()->constrained('anggota')->nullOnDelete();
            $table->foreignId('ppob_produk_id')->constrained('ppob_produk');
            $table->string('nomor')->unique();
            $table->string('no_tujuan', 30); // nomor HP / ID pelanggan
            $table->bigInteger('harga');
            $table->bigInteger('harga_beli');
            $table->bigInteger('laba')->default(0);
            $table->string('status')->default('pending'); // pending, sukses, gagal, refund
            $table->string('sn')->nullable(); // serial number / kode redeem
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppob_transaksi');
        Schema::dropIfExists('ppob_produk');
    }
};
