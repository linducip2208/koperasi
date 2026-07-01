<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $t) {
            $t->foreignId('tenant_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
            $t->foreignId('cabang_id')->nullable()->after('tenant_id')->constrained('cabang')->nullOnDelete();
            $t->string('nip')->nullable()->after('name');
            $t->string('telp')->nullable()->after('email');
            $t->string('avatar_path')->nullable()->after('telp');
            $t->boolean('aktif')->default(true)->after('avatar_path');
            $t->string('two_factor_secret')->nullable();
            $t->text('two_factor_recovery_codes')->nullable();
            $t->timestamp('last_login_at')->nullable();
            $t->string('last_login_ip')->nullable();
            $t->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $t) {
            $t->dropForeign(['tenant_id']);
            $t->dropForeign(['cabang_id']);
            $t->dropColumn(['tenant_id', 'cabang_id', 'nip', 'telp', 'avatar_path', 'aktif',
                'two_factor_secret', 'two_factor_recovery_codes', 'last_login_at', 'last_login_ip', 'deleted_at']);
        });
    }
};
