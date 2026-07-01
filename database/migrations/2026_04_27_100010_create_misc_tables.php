<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Unit Produsen
        Schema::create('unit_produsen_komoditi', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $t->string('kode', 20);
            $t->string('nama');
            $t->string('jenis')->nullable(); // tani|ternak|kerajinan|pengolahan
            $t->string('satuan')->default('kg');
            $t->bigInteger('harga_beli_default')->default(0);
            $t->bigInteger('harga_jual_default')->default(0);
            $t->boolean('aktif')->default(true);
            $t->timestamps();
        });

        Schema::create('unit_produsen_setoran', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $t->foreignId('anggota_id')->constrained('anggota');
            $t->foreignId('komoditi_id')->constrained('unit_produsen_komoditi');
            $t->date('tanggal');
            $t->decimal('jumlah', 14, 3);
            $t->bigInteger('harga_satuan');
            $t->bigInteger('total');
            $t->string('kualitas')->nullable();
            $t->text('catatan')->nullable();
            $t->bigInteger('terbayar')->default(0);
            $t->foreignId('kas_id')->nullable()->constrained('kas');
            $t->foreignId('jurnal_id')->nullable()->constrained('jurnal')->nullOnDelete();
            $t->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamps();
        });

        // Unit Jasa
        Schema::create('unit_jasa_layanan', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $t->string('kode', 20);
            $t->string('nama');
            $t->bigInteger('tarif')->default(0);
            $t->string('satuan_tarif')->nullable(); // per_jam|per_hari|paket
            $t->boolean('aktif')->default(true);
            $t->timestamps();
        });

        Schema::create('unit_jasa_order', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $t->foreignId('layanan_id')->constrained('unit_jasa_layanan');
            $t->foreignId('anggota_id')->nullable()->constrained('anggota')->nullOnDelete();
            $t->string('nomor', 30)->unique();
            $t->date('tanggal');
            $t->string('nama_pelanggan')->nullable();
            $t->bigInteger('total');
            $t->bigInteger('komisi_anggota')->default(0);
            $t->string('status')->default('booking'); // booking|berjalan|selesai|batal
            $t->bigInteger('bayar')->default(0);
            $t->foreignId('kas_id')->nullable()->constrained('kas');
            $t->foreignId('jurnal_id')->nullable()->constrained('jurnal')->nullOnDelete();
            $t->timestamps();
        });

        // Iuran & Tagihan
        Schema::create('tagihan_master', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $t->string('kode', 20);
            $t->string('nama');
            $t->bigInteger('nominal');
            $t->string('siklus')->default('bulanan'); // bulanan|tahunan|satu_kali
            $t->foreignId('coa_id')->nullable()->constrained('coa');
            $t->boolean('auto_potong_simpanan')->default(false);
            $t->boolean('aktif')->default(true);
            $t->timestamps();
        });

        Schema::create('tagihan', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $t->foreignId('master_id')->constrained('tagihan_master');
            $t->foreignId('anggota_id')->constrained('anggota');
            $t->string('nomor', 30)->unique();
            $t->date('periode'); // tanggal periode tagihan
            $t->date('jatuh_tempo');
            $t->bigInteger('nominal');
            $t->bigInteger('terbayar')->default(0);
            $t->string('status')->default('belum_bayar'); // belum_bayar|sebagian|lunas|batal
            $t->date('tanggal_bayar')->nullable();
            $t->foreignId('kas_id')->nullable()->constrained('kas');
            $t->foreignId('simpanan_id')->nullable()->constrained('simpanan');
            $t->foreignId('jurnal_id')->nullable()->constrained('jurnal')->nullOnDelete();
            $t->timestamps();
            $t->index(['tenant_id', 'status']);
        });

        // Asuransi
        Schema::create('asuransi_produk', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $t->string('nama');
            $t->string('penanggung')->nullable(); // Jamkrindo, dll
            $t->string('jenis'); // kredit|jiwa
            $t->decimal('rate_premi', 5, 4)->default(0);
            $t->boolean('aktif')->default(true);
            $t->timestamps();
        });

        Schema::create('asuransi_polis', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $t->foreignId('produk_id')->constrained('asuransi_produk');
            $t->foreignId('pinjaman_id')->nullable()->constrained('pinjaman')->nullOnDelete();
            $t->foreignId('anggota_id')->constrained('anggota');
            $t->string('nomor_polis', 50)->unique();
            $t->bigInteger('nilai_pertanggungan');
            $t->bigInteger('premi');
            $t->date('tanggal_mulai');
            $t->date('tanggal_akhir');
            $t->string('status')->default('aktif'); // aktif|berakhir|klaim
            $t->timestamps();
        });

        Schema::create('asuransi_klaim', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $t->foreignId('polis_id')->constrained('asuransi_polis');
            $t->date('tanggal_kejadian');
            $t->date('tanggal_pengajuan');
            $t->bigInteger('nilai_klaim');
            $t->text('uraian')->nullable();
            $t->string('status')->default('pengajuan'); // pengajuan|disetujui|ditolak|dibayar
            $t->bigInteger('nilai_diterima')->default(0);
            $t->date('tanggal_diterima')->nullable();
            $t->timestamps();
        });

        // HR
        Schema::create('karyawan', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $t->foreignId('cabang_id')->nullable()->constrained('cabang')->nullOnDelete();
            $t->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $t->string('nip', 30)->unique();
            $t->string('nama');
            $t->string('jabatan')->nullable();
            $t->string('departemen')->nullable();
            $t->date('tanggal_masuk')->nullable();
            $t->date('tanggal_keluar')->nullable();
            $t->bigInteger('gaji_pokok')->default(0);
            $t->json('tunjangan')->nullable();
            $t->string('npwp', 25)->nullable();
            $t->string('no_bpjs_kesehatan', 30)->nullable();
            $t->string('no_bpjs_naker', 30)->nullable();
            $t->string('rekening_bank')->nullable();
            $t->string('nomor_rekening')->nullable();
            $t->string('status')->default('aktif'); // aktif|cuti|resign|pensiun
            $t->softDeletes();
            $t->timestamps();
        });

        Schema::create('gaji', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $t->foreignId('karyawan_id')->constrained('karyawan');
            $t->integer('tahun');
            $t->integer('bulan');
            $t->bigInteger('gaji_pokok');
            $t->bigInteger('total_tunjangan')->default(0);
            $t->bigInteger('lembur')->default(0);
            $t->bigInteger('total_potongan')->default(0);
            $t->bigInteger('pph21')->default(0);
            $t->bigInteger('bpjs_potongan')->default(0);
            $t->bigInteger('total_bruto');
            $t->bigInteger('total_netto');
            $t->json('detail')->nullable();
            $t->date('tanggal_bayar')->nullable();
            $t->string('status')->default('draft'); // draft|disetujui|dibayar
            $t->foreignId('jurnal_id')->nullable()->constrained('jurnal')->nullOnDelete();
            $t->timestamps();
            $t->unique(['tenant_id', 'karyawan_id', 'tahun', 'bulan']);
        });

        // Asset
        Schema::create('asset', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $t->foreignId('cabang_id')->nullable()->constrained('cabang')->nullOnDelete();
            $t->string('kode', 30)->unique();
            $t->string('nama');
            $t->string('kategori')->nullable();
            $t->date('tanggal_perolehan');
            $t->bigInteger('harga_perolehan');
            $t->bigInteger('nilai_residu')->default(0);
            $t->integer('umur_ekonomis_bulan');
            $t->string('metode_susut')->default('garis_lurus'); // garis_lurus|saldo_menurun
            $t->bigInteger('akumulasi_susut')->default(0);
            $t->bigInteger('nilai_buku')->default(0);
            $t->foreignId('coa_aset_id')->nullable()->constrained('coa');
            $t->foreignId('coa_susut_id')->nullable()->constrained('coa');
            $t->foreignId('coa_akumulasi_id')->nullable()->constrained('coa');
            $t->string('status')->default('aktif'); // aktif|dijual|rusak|hapus
            $t->date('tanggal_dilepas')->nullable();
            $t->softDeletes();
            $t->timestamps();
        });

        Schema::create('asset_penyusutan', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $t->foreignId('asset_id')->constrained('asset')->cascadeOnDelete();
            $t->date('periode');
            $t->bigInteger('jumlah');
            $t->bigInteger('akumulasi');
            $t->bigInteger('nilai_buku');
            $t->foreignId('jurnal_id')->nullable()->constrained('jurnal')->nullOnDelete();
            $t->timestamps();
        });

        // RAT
        Schema::create('rat', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $t->integer('tahun_buku');
            $t->date('tanggal');
            $t->string('lokasi')->nullable();
            $t->json('agenda')->nullable();
            $t->integer('jumlah_anggota_terdaftar')->default(0);
            $t->integer('jumlah_hadir')->default(0);
            $t->integer('quorum_persen')->default(50);
            $t->boolean('quorum_tercapai')->default(false);
            $t->text('notulen')->nullable();
            $t->json('keputusan')->nullable();
            $t->string('status')->default('rencana'); // rencana|berlangsung|selesai|batal
            $t->timestamps();
            $t->unique(['tenant_id', 'tahun_buku']);
        });

        Schema::create('rat_kehadiran', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $t->foreignId('rat_id')->constrained('rat')->cascadeOnDelete();
            $t->foreignId('anggota_id')->constrained('anggota');
            $t->timestamp('checkin_at')->nullable();
            $t->string('metode')->default('manual'); // manual|qr|online
            $t->timestamps();
            $t->unique(['rat_id', 'anggota_id']);
        });

        // Notifikasi
        Schema::create('notifikasi_template', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $t->string('kode', 50);
            $t->string('nama');
            $t->string('event'); // angsuran_h_minus_3, simpanan_setor, dll
            $t->string('channel'); // email|whatsapp|sms|inapp
            $t->string('subject')->nullable();
            $t->text('body');
            $t->boolean('aktif')->default(true);
            $t->timestamps();
            $t->unique(['tenant_id', 'kode', 'channel']);
        });

        // Numbering — auto generate nomor (anggota, transaksi, dll)
        Schema::create('numbering_setting', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $t->string('kode', 30); // anggota|jurnal|simpanan_trx|pinjaman|...
            $t->string('prefix')->nullable();
            $t->string('format')->default('{prefix}{ymd}{seq:5}'); // template
            $t->integer('panjang_seq')->default(5);
            $t->integer('reset_period')->default(0); // 0=never, 1=daily, 2=monthly, 3=yearly
            $t->bigInteger('next_number')->default(1);
            $t->date('last_reset_at')->nullable();
            $t->timestamps();
            $t->unique(['tenant_id', 'kode']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('numbering_setting');
        Schema::dropIfExists('notifikasi_template');
        Schema::dropIfExists('rat_kehadiran');
        Schema::dropIfExists('rat');
        Schema::dropIfExists('asset_penyusutan');
        Schema::dropIfExists('asset');
        Schema::dropIfExists('gaji');
        Schema::dropIfExists('karyawan');
        Schema::dropIfExists('asuransi_klaim');
        Schema::dropIfExists('asuransi_polis');
        Schema::dropIfExists('asuransi_produk');
        Schema::dropIfExists('tagihan');
        Schema::dropIfExists('tagihan_master');
        Schema::dropIfExists('unit_jasa_order');
        Schema::dropIfExists('unit_jasa_layanan');
        Schema::dropIfExists('unit_produsen_setoran');
        Schema::dropIfExists('unit_produsen_komoditi');
    }
};
