<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('anggota', function (Blueprint $table) {
            $table->foreignId('parent_anggota_id')->nullable()->after('tanggal_masuk')
                ->constrained('anggota')->nullOnDelete();
            $table->string('hubungan_keluarga', 30)->nullable()->after('parent_anggota_id');
        });
    }

    public function down(): void
    {
        Schema::table('anggota', function (Blueprint $table) {
            $table->dropForeign(['parent_anggota_id']);
            $table->dropColumn(['parent_anggota_id', 'hubungan_keluarga']);
        });
    }
};
