<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rat_voting', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('rat_id')->constrained('rat')->cascadeOnDelete();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->json('opsi'); // ['Ya', 'Tidak'] atau opsi custom
            $table->timestamp('mulai')->nullable();
            $table->timestamp('selesai')->nullable();
            $table->boolean('is_aktif')->default(false);
            $table->timestamps();
        });

        Schema::create('rat_voting_suara', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voting_id')->constrained('rat_voting')->cascadeOnDelete();
            $table->foreignId('anggota_id')->constrained('anggota');
            $table->integer('opsi_index'); // index opsi yang dipilih
            $table->timestamps();
            $table->unique(['voting_id', 'anggota_id']); // 1 anggota 1 suara per voting
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rat_voting_suara');
        Schema::dropIfExists('rat_voting');
    }
};
