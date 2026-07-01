<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('produk_pinjaman', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $t->string('kode', 20);
            $t->string('nama');
            $t->string('jenis')->default('produktif'); // produktif|konsumtif|multiguna
            $t->string('akad_type')->default('konvensional');
            // konvensional: flat|efektif|anuitas
            // syariah: murabahah|mudharabah|musyarakah|ijarah|ijarah_mb|qardh|rahn|salam|istishna
            $t->string('metode_perhitungan');
            $t->bigInteger('plafon_minimum')->default(0);
            $t->bigInteger('plafon_maksimum')->default(0);
            $t->integer('tenor_minimum')->default(1);
            $t->integer('tenor_maksimum')->default(60);
            $t->decimal('bunga_persen', 8, 4)->default(0); // utk konvensional
            $t->decimal('margin_persen', 8, 4)->default(0); // utk syariah jual-beli
            $t->decimal('nisbah_anggota', 5, 2)->default(0); // utk syariah bagi hasil
            $t->decimal('nisbah_koperasi', 5, 2)->default(0);
            $t->decimal('biaya_admin_persen', 5, 2)->default(0);
            $t->bigInteger('biaya_admin_flat')->default(0);
            $t->decimal('biaya_provisi_persen', 5, 2)->default(0);
            $t->decimal('biaya_asuransi_persen', 5, 2)->default(0);
            $t->bigInteger('biaya_materai')->default(0);
            $t->decimal('denda_persen_per_hari', 5, 4)->default(0);
            $t->bigInteger('denda_flat_per_hari')->default(0);
            $t->boolean('butuh_jaminan')->default(false);
            $t->boolean('butuh_simpanan_blokir')->default(false);
            $t->decimal('rasio_simpanan_blokir', 5, 2)->default(0);
            $t->foreignId('coa_pokok_id')->nullable()->constrained('coa');
            $t->foreignId('coa_bunga_id')->nullable()->constrained('coa');
            $t->foreignId('coa_denda_id')->nullable()->constrained('coa');
            $t->boolean('aktif')->default(true);
            $t->json('meta')->nullable();
            $t->softDeletes();
            $t->timestamps();
            $t->unique(['tenant_id', 'kode']);
        });

        Schema::create('pinjaman', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $t->foreignId('cabang_id')->nullable()->constrained('cabang')->nullOnDelete();
            $t->foreignId('anggota_id')->constrained('anggota');
            $t->foreignId('produk_id')->constrained('produk_pinjaman');
            $t->string('nomor_akad', 40)->unique();
            $t->date('tanggal_pengajuan');
            $t->date('tanggal_akad')->nullable();
            $t->date('tanggal_pencairan')->nullable();
            $t->date('tanggal_jatuh_tempo')->nullable();
            $t->bigInteger('plafon');
            $t->bigInteger('pokok'); // pokok pembiayaan = plafon utk konv, harga jual utk murabahah
            $t->bigInteger('margin_total')->default(0); // utk syariah jual beli
            $t->bigInteger('total_bayar')->default(0); // pokok + margin/bunga total
            $t->integer('tenor');
            $t->decimal('bunga_persen', 8, 4)->default(0);
            $t->decimal('margin_persen', 8, 4)->default(0);
            $t->decimal('nisbah_anggota', 5, 2)->default(0);
            $t->decimal('nisbah_koperasi', 5, 2)->default(0);
            $t->bigInteger('biaya_admin')->default(0);
            $t->bigInteger('biaya_provisi')->default(0);
            $t->bigInteger('biaya_asuransi')->default(0);
            $t->bigInteger('biaya_materai')->default(0);
            $t->bigInteger('total_biaya')->default(0);
            $t->bigInteger('pencairan_bersih')->default(0);
            $t->bigInteger('saldo_pokok')->default(0); // sisa pokok belum lunas
            $t->bigInteger('saldo_margin')->default(0);
            $t->text('tujuan')->nullable();
            $t->text('analisa_kredit')->nullable();
            $t->json('survey_data')->nullable();
            $t->string('status')->default('pengajuan');
            // pengajuan|survey|analisa|approval|akad|aktif|lunas|macet|hapus_buku|hapus_tagih|ditolak|dibatalkan
            $t->string('kolektabilitas')->default('lancar'); // lancar|dpk|kurang_lancar|diragukan|macet
            $t->integer('tunggakan_hari')->default(0);
            $t->bigInteger('tunggakan_pokok')->default(0);
            $t->bigInteger('tunggakan_margin')->default(0);
            $t->bigInteger('tunggakan_denda')->default(0);
            $t->foreignId('ao_id')->nullable()->constrained('users')->nullOnDelete(); // account officer
            $t->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamp('approved_at')->nullable();
            $t->foreignId('rejected_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamp('rejected_at')->nullable();
            $t->text('alasan_tolak')->nullable();
            $t->json('meta')->nullable();
            $t->softDeletes();
            $t->timestamps();
            $t->index(['tenant_id', 'status']);
            $t->index(['tenant_id', 'kolektabilitas']);
            $t->index(['tenant_id', 'anggota_id']);
        });

        Schema::create('pinjaman_jadwal', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $t->foreignId('pinjaman_id')->constrained('pinjaman')->cascadeOnDelete();
            $t->integer('angsuran_ke');
            $t->date('tanggal_jatuh_tempo');
            $t->bigInteger('pokok');
            $t->bigInteger('margin'); // bunga atau margin
            $t->bigInteger('total_angsuran');
            $t->bigInteger('saldo_pokok'); // sisa pokok setelah angsuran ini
            $t->bigInteger('terbayar_pokok')->default(0);
            $t->bigInteger('terbayar_margin')->default(0);
            $t->bigInteger('denda')->default(0);
            $t->bigInteger('terbayar_denda')->default(0);
            $t->date('tanggal_bayar')->nullable();
            $t->string('status')->default('belum_jatuh_tempo'); // belum_jatuh_tempo|jatuh_tempo|lunas|telat
            $t->timestamps();
            $t->index(['tenant_id', 'pinjaman_id', 'angsuran_ke']);
        });

        Schema::create('pinjaman_pembayaran', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $t->foreignId('pinjaman_id')->constrained('pinjaman');
            $t->string('nomor', 30)->unique();
            $t->date('tanggal');
            $t->string('jenis')->default('angsuran'); // angsuran|pelunasan|denda|admin|topup
            $t->bigInteger('total_bayar');
            $t->bigInteger('alokasi_pokok')->default(0);
            $t->bigInteger('alokasi_margin')->default(0);
            $t->bigInteger('alokasi_denda')->default(0);
            $t->bigInteger('alokasi_admin')->default(0);
            $t->bigInteger('alokasi_titipan')->default(0); // kelebihan bayar
            $t->foreignId('kas_id')->nullable()->constrained('kas');
            $t->string('metode_bayar')->nullable();
            $t->text('keterangan')->nullable();
            $t->foreignId('jurnal_id')->nullable()->constrained('jurnal')->nullOnDelete();
            $t->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamps();
            $t->index(['tenant_id', 'tanggal']);
        });

        Schema::create('pinjaman_jaminan', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $t->foreignId('pinjaman_id')->constrained('pinjaman')->cascadeOnDelete();
            $t->string('jenis'); // bpkb|sertifikat|simpanan|deposito|emas|surat_berharga
            $t->string('nama'); // ringkas: BPKB Honda 2020 / Sertifikat Tanah Sleman
            $t->string('nomor_dokumen')->nullable();
            $t->string('atas_nama')->nullable();
            $t->bigInteger('nilai_taksiran');
            $t->bigInteger('nilai_pasar')->nullable();
            $t->decimal('ltv', 5, 2)->nullable(); // loan to value ratio
            $t->json('foto_path')->nullable();
            $t->json('dokumen_path')->nullable();
            $t->string('status')->default('aktif'); // aktif|dilepas|dieksekusi
            $t->date('tanggal_lepas')->nullable();
            $t->text('catatan')->nullable();
            $t->timestamps();
        });

        Schema::create('pinjaman_approval', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $t->foreignId('pinjaman_id')->constrained('pinjaman')->cascadeOnDelete();
            $t->integer('level'); // 1=AO, 2=manajer, 3=ketua, dst
            $t->string('jabatan')->nullable();
            $t->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $t->string('keputusan')->default('pending'); // pending|setuju|tolak
            $t->text('catatan')->nullable();
            $t->timestamp('decided_at')->nullable();
            $t->timestamps();
        });

        Schema::create('pinjaman_restrukturisasi', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $t->foreignId('pinjaman_id')->constrained('pinjaman');
            $t->date('tanggal');
            $t->string('jenis'); // perpanjangan|reschedule|reconditioning|restructuring
            $t->json('sebelum'); // snapshot data lama
            $t->json('sesudah'); // data baru
            $t->text('alasan');
            $t->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pinjaman_restrukturisasi');
        Schema::dropIfExists('pinjaman_approval');
        Schema::dropIfExists('pinjaman_jaminan');
        Schema::dropIfExists('pinjaman_pembayaran');
        Schema::dropIfExists('pinjaman_jadwal');
        Schema::dropIfExists('pinjaman');
        Schema::dropIfExists('produk_pinjaman');
    }
};
