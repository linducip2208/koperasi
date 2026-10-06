<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // NIK unik (NULL ganda tetap diizinkan MySQL/SQLite) — deteksi duplikat anggota.
        $exists = collect(Schema::getIndexes('anggota'))->contains(fn ($i) => ($i['name'] ?? '') === 'anggota_nik_unique');
        if (! $exists) {
            Schema::table('anggota', function (Blueprint $table) {
                $table->unique('nik', 'anggota_nik_unique');
            });
        }
    }

    public function down(): void
    {
        Schema::table('anggota', function (Blueprint $table) {
            $table->dropUnique('anggota_nik_unique');
        });
    }
};
