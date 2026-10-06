<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Kolom identitas koperasi tunggal (1 instalasi = 1 koperasi). Semua nullable/additive. */
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->string('short_name', 50)->nullable()->after('nama');
            $table->date('tanggal_akta')->nullable()->after('akta_pendirian');
            $table->string('logo_dark_path')->nullable()->after('logo_path');
            $table->string('favicon_path')->nullable()->after('logo_dark_path');
            $table->string('slogan')->nullable()->after('favicon_path');
            $table->string('desa', 100)->nullable()->after('alamat');
            $table->string('kecamatan', 100)->nullable()->after('desa');
            $table->string('kabupaten', 100)->nullable()->after('kecamatan');
            $table->string('provinsi', 100)->nullable()->after('kabupaten');
            $table->string('kode_pos', 10)->nullable()->after('provinsi');
            $table->string('whatsapp', 30)->nullable()->after('telp');
            $table->string('nama_ketua')->nullable()->after('website');
            $table->string('nama_sekretaris')->nullable()->after('nama_ketua');
            $table->string('nama_bendahara')->nullable()->after('nama_sekretaris');
            $table->string('timezone', 50)->default('Asia/Jakarta')->after('mata_uang');
            $table->string('theme', 30)->default('emerald')->after('timezone');
            $table->string('primary_color', 10)->default('#059669')->after('theme');
            $table->string('secondary_color', 10)->default('#0d9488')->after('primary_color');
            $table->string('footer_text')->nullable()->after('secondary_color');
            $table->string('prefix_invoice', 10)->default('INV-')->after('footer_text');
            $table->string('prefix_member', 10)->default('AGT-')->after('prefix_invoice');
            $table->string('prefix_loan', 10)->default('PJM-')->after('prefix_member');
            $table->string('prefix_savings', 10)->default('STR-')->after('prefix_loan');
        });
    }

    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn([
                'short_name', 'tanggal_akta', 'logo_dark_path', 'favicon_path', 'slogan',
                'desa', 'kecamatan', 'kabupaten', 'provinsi', 'kode_pos', 'whatsapp',
                'nama_ketua', 'nama_sekretaris', 'nama_bendahara', 'timezone', 'theme',
                'primary_color', 'secondary_color', 'footer_text',
                'prefix_invoice', 'prefix_member', 'prefix_loan', 'prefix_savings',
            ]);
        });
    }
};
