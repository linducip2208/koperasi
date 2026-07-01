<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tiket', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('anggota_id')->nullable()->constrained('anggota')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nomor', 30)->unique();
            $table->string('subjek');
            $table->text('deskripsi');
            $table->string('kategori')->default('umum'); // umum|simpanan|pinjaman|teknis|keluhan
            $table->string('prioritas')->default('normal'); // rendah|normal|tinggi|urgent
            $table->string('status')->default('terbuka'); // terbuka|proses|menunggu|selesai|tutup
            $table->timestamp('tanggal_selesai')->nullable();
            $table->integer('sla_jam')->default(24);
            $table->timestamps();
        });

        Schema::create('tiket_balasan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tiket_id')->constrained('tiket')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('pengirim')->default('admin'); // admin|anggota
            $table->text('pesan');
            $table->json('lampiran')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tiket_balasan');
        Schema::dropIfExists('tiket');
    }
};
