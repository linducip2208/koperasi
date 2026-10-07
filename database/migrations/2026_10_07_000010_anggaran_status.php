<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('anggaran') && ! Schema::hasColumn('anggaran', 'status')) {
            Schema::table('anggaran', function (Blueprint $table) {
                $table->string('status', 20)->default('draft')->after('des');
                $table->foreignId('approved_by')->nullable()->after('status')->constrained('users')->nullOnDelete();
                $table->timestamp('approved_at')->nullable()->after('approved_by');
            });
            // Data existing sedang dipakai → anggap disetujui agar laporan tidak kosong.
            \Illuminate\Support\Facades\DB::table('anggaran')->whereNull('status')->orWhere('status', '')->update(['status' => 'approved']);
            \Illuminate\Support\Facades\DB::table('anggaran')->where('status', 'draft')->update(['status' => 'approved']);
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('anggaran', 'status')) {
            Schema::table('anggaran', function (Blueprint $table) {
                $table->dropConstrainedForeignId('approved_by');
                $table->dropColumn(['status', 'approved_at']);
            });
        }
    }
};
