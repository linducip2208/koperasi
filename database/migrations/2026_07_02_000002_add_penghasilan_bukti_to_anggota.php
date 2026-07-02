<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('anggota', function (Blueprint $t) {
            $t->string('penghasilan_bukti')->nullable()->after('penghasilan_bulanan');
        });
    }

    public function down(): void
    {
        Schema::table('anggota', function (Blueprint $t) {
            $t->dropColumn('penghasilan_bukti');
        });
    }
};
