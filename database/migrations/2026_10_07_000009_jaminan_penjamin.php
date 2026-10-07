<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Penjamin (guarantor) per agunan — additive. */
    public function up(): void
    {
        if (Schema::hasTable('pinjaman_jaminan') && ! Schema::hasColumn('pinjaman_jaminan', 'penjamin_nama')) {
            Schema::table('pinjaman_jaminan', function (Blueprint $table) {
                $table->string('penjamin_nama')->nullable()->after('catatan');
                $table->string('penjamin_nik', 25)->nullable()->after('penjamin_nama');
                $table->string('penjamin_telp', 30)->nullable()->after('penjamin_nik');
                $table->string('penjamin_hubungan', 50)->nullable()->after('penjamin_telp');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('pinjaman_jaminan', 'penjamin_nama')) {
            Schema::table('pinjaman_jaminan', function (Blueprint $table) {
                $table->dropColumn(['penjamin_nama', 'penjamin_nik', 'penjamin_telp', 'penjamin_hubungan']);
            });
        }
    }
};
