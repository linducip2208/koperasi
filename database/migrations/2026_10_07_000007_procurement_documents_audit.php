<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('procurements')) {
            Schema::create('procurements', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
                $table->string('nomor', 40)->unique();
                $table->string('judul');
                $table->text('deskripsi')->nullable();
                $table->foreignId('supplier_id')->nullable()->constrained('toko_supplier')->nullOnDelete();
                $table->bigInteger('estimasi')->default(0);
                $table->bigInteger('aktual')->nullable();
                $table->string('status')->default('draft'); // draft|submitted|review|approved|rejected|revision|executed|closed
                $table->text('catatan')->nullable();
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
                $table->index(['tenant_id', 'status']);
            });
        }

        if (! Schema::hasTable('member_documents')) {
            Schema::create('member_documents', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
                $table->foreignId('anggota_id')->constrained('anggota')->cascadeOnDelete();
                $table->string('jenis', 40); // ktp|kk|npwp|kontrak|agunan|surat|rat|laporan|kuitansi|lainnya
                $table->string('nama');
                $table->string('file_path');
                $table->string('mime', 100)->nullable();
                $table->bigInteger('ukuran')->default(0);
                $table->integer('versi')->default(1);
                $table->date('tanggal_berlaku')->nullable();
                $table->date('tanggal_kedaluwarsa')->nullable();
                $table->string('status')->default('aktif'); // aktif|kedaluwarsa|arsip
                $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
                $table->index(['anggota_id', 'jenis']);
                $table->index('tanggal_kedaluwarsa');
            });
        }

        if (! Schema::hasTable('audit_findings')) {
            Schema::create('audit_findings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
                $table->string('judul');
                $table->text('deskripsi')->nullable();
                $table->string('kategori', 40)->default('keuangan'); // keuangan|keanggotaan|operasional|kepatuhan|ti
                $table->string('severity', 20)->default('medium'); // low|medium|high|critical
                $table->string('status')->default('open'); // open|progress|resolved|closed
                $table->text('tindak_lanjut')->nullable();
                $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
                $table->date('due_date')->nullable();
                $table->timestamp('resolved_at')->nullable();
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
                $table->index(['tenant_id', 'status']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_findings');
        Schema::dropIfExists('member_documents');
        Schema::dropIfExists('procurements');
    }
};
