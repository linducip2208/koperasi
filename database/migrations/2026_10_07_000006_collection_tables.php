<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('pinjaman') && ! Schema::hasColumn('pinjaman', 'kolektor_id')) {
            Schema::table('pinjaman', function (Blueprint $table) {
                $table->foreignId('kolektor_id')->nullable()->after('ao_id')->constrained('users')->nullOnDelete();
            });
        }

        if (! Schema::hasTable('collection_followups')) {
            Schema::create('collection_followups', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
                $table->foreignId('pinjaman_id')->constrained('pinjaman')->cascadeOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('jenis', 30); // kunjungan|telepon|wa|janji_bayar|eskalasi|bayar
                $table->text('catatan')->nullable();
                $table->bigInteger('janji_nominal')->nullable();
                $table->date('janji_tanggal')->nullable();
                $table->string('bukti_path')->nullable();
                $table->timestamps();
                $table->index(['pinjaman_id', 'created_at']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('collection_followups');
        if (Schema::hasColumn('pinjaman', 'kolektor_id')) {
            Schema::table('pinjaman', function (Blueprint $table) {
                $table->dropConstrainedForeignId('kolektor_id');
            });
        }
    }
};
