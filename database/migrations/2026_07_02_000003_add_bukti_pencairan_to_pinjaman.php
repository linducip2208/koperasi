<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pinjaman', function (Blueprint $t) {
            $t->string('bukti_pencairan')->nullable()->after('pencairan_bersih');
        });
    }

    public function down(): void
    {
        Schema::table('pinjaman', function (Blueprint $t) {
            $t->dropColumn('bukti_pencairan');
        });
    }
};
