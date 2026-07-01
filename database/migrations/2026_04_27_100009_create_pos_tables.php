<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('toko_kategori', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $t->foreignId('parent_id')->nullable()->constrained('toko_kategori')->nullOnDelete();
            $t->string('nama');
            $t->boolean('aktif')->default(true);
            $t->softDeletes();
            $t->timestamps();
        });

        Schema::create('toko_supplier', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $t->string('kode', 20);
            $t->string('nama');
            $t->string('telp')->nullable();
            $t->string('email')->nullable();
            $t->text('alamat')->nullable();
            $t->string('npwp', 25)->nullable();
            $t->bigInteger('saldo_hutang')->default(0);
            $t->boolean('aktif')->default(true);
            $t->softDeletes();
            $t->timestamps();
            $t->unique(['tenant_id', 'kode']);
        });

        Schema::create('toko_satuan', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $t->string('kode', 10);
            $t->string('nama');
            $t->timestamps();
            $t->unique(['tenant_id', 'kode']);
        });

        Schema::create('toko_barang', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $t->string('sku', 30);
            $t->string('barcode', 50)->nullable()->index();
            $t->string('nama');
            $t->foreignId('kategori_id')->nullable()->constrained('toko_kategori')->nullOnDelete();
            $t->foreignId('satuan_id')->nullable()->constrained('toko_satuan')->nullOnDelete();
            $t->string('brand')->nullable();
            $t->bigInteger('harga_beli')->default(0);
            $t->bigInteger('harga_jual_umum')->default(0);
            $t->bigInteger('harga_jual_anggota')->default(0);
            $t->bigInteger('harga_jual_grosir')->default(0);
            $t->integer('stok')->default(0);
            $t->integer('stok_minimum')->default(0);
            $t->integer('stok_maksimum')->default(0);
            $t->string('foto_path')->nullable();
            $t->string('metode_hpp')->default('average'); // average|fifo
            $t->boolean('is_jasa')->default(false);
            $t->boolean('aktif')->default(true);
            $t->json('meta')->nullable();
            $t->softDeletes();
            $t->timestamps();
            $t->unique(['tenant_id', 'sku']);
        });

        Schema::create('toko_barang_satuan', function (Blueprint $t) {
            // multi-satuan: 1 dus = 12 pcs
            $t->id();
            $t->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $t->foreignId('barang_id')->constrained('toko_barang')->cascadeOnDelete();
            $t->foreignId('satuan_id')->constrained('toko_satuan');
            $t->integer('konversi'); // ke satuan dasar
            $t->bigInteger('harga_jual_umum')->default(0);
            $t->bigInteger('harga_jual_anggota')->default(0);
            $t->timestamps();
        });

        Schema::create('toko_stok_mutasi', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $t->foreignId('barang_id')->constrained('toko_barang');
            $t->foreignId('cabang_id')->nullable()->constrained('cabang')->nullOnDelete();
            $t->date('tanggal');
            $t->string('jenis'); // pembelian|penjualan|retur_beli|retur_jual|opname|adjustment|mutasi_masuk|mutasi_keluar
            $t->integer('jumlah'); // signed: + masuk, - keluar
            $t->bigInteger('harga_satuan')->default(0);
            $t->integer('stok_sebelum');
            $t->integer('stok_sesudah');
            $t->string('referensi_type')->nullable();
            $t->unsignedBigInteger('referensi_id')->nullable();
            $t->text('keterangan')->nullable();
            $t->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamps();
            $t->index(['tenant_id', 'tanggal']);
            $t->index(['barang_id', 'tanggal']);
        });

        Schema::create('toko_pembelian', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $t->foreignId('cabang_id')->nullable()->constrained('cabang')->nullOnDelete();
            $t->foreignId('supplier_id')->nullable()->constrained('toko_supplier');
            $t->string('nomor', 30)->unique();
            $t->date('tanggal');
            $t->date('tanggal_jatuh_tempo')->nullable();
            $t->bigInteger('subtotal');
            $t->bigInteger('diskon')->default(0);
            $t->bigInteger('pajak')->default(0);
            $t->bigInteger('biaya_lain')->default(0);
            $t->bigInteger('total');
            $t->bigInteger('terbayar')->default(0);
            $t->string('status')->default('draft'); // draft|diterima|dibayar|lunas|batal
            $t->text('keterangan')->nullable();
            $t->foreignId('jurnal_id')->nullable()->constrained('jurnal')->nullOnDelete();
            $t->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamps();
        });

        Schema::create('toko_pembelian_detail', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $t->foreignId('pembelian_id')->constrained('toko_pembelian')->cascadeOnDelete();
            $t->foreignId('barang_id')->constrained('toko_barang');
            $t->integer('jumlah');
            $t->bigInteger('harga_satuan');
            $t->bigInteger('diskon')->default(0);
            $t->bigInteger('subtotal');
            $t->timestamps();
        });

        Schema::create('toko_penjualan', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $t->foreignId('cabang_id')->nullable()->constrained('cabang')->nullOnDelete();
            $t->foreignId('anggota_id')->nullable()->constrained('anggota')->nullOnDelete(); // null = umum
            $t->string('nomor', 30)->unique();
            $t->date('tanggal');
            $t->bigInteger('subtotal');
            $t->bigInteger('diskon')->default(0);
            $t->bigInteger('pajak')->default(0);
            $t->bigInteger('total');
            $t->bigInteger('bayar')->default(0);
            $t->bigInteger('kembali')->default(0);
            $t->string('metode_bayar')->default('cash'); // cash|transfer|qris|potong_simpanan|kasbon
            $t->foreignId('kas_id')->nullable()->constrained('kas');
            $t->foreignId('simpanan_id')->nullable()->constrained('simpanan'); // jika potong simpanan
            $t->string('status')->default('lunas'); // lunas|kasbon|batal
            $t->foreignId('jurnal_id')->nullable()->constrained('jurnal')->nullOnDelete();
            $t->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // kasir
            $t->timestamps();
            $t->index(['tenant_id', 'tanggal']);
        });

        Schema::create('toko_penjualan_detail', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $t->foreignId('penjualan_id')->constrained('toko_penjualan')->cascadeOnDelete();
            $t->foreignId('barang_id')->constrained('toko_barang');
            $t->integer('jumlah');
            $t->bigInteger('harga_satuan');
            $t->bigInteger('diskon')->default(0);
            $t->bigInteger('subtotal');
            $t->bigInteger('hpp')->default(0);
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('toko_penjualan_detail');
        Schema::dropIfExists('toko_penjualan');
        Schema::dropIfExists('toko_pembelian_detail');
        Schema::dropIfExists('toko_pembelian');
        Schema::dropIfExists('toko_stok_mutasi');
        Schema::dropIfExists('toko_barang_satuan');
        Schema::dropIfExists('toko_barang');
        Schema::dropIfExists('toko_satuan');
        Schema::dropIfExists('toko_supplier');
        Schema::dropIfExists('toko_kategori');
    }
};
