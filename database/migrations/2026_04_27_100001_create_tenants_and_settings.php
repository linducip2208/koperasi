<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $t) {
            $t->id();
            $t->string('nama');
            $t->string('badan_hukum')->nullable();
            $t->string('nik_koperasi')->nullable();
            $t->string('npwp')->nullable();
            $t->string('akta_pendirian')->nullable();
            $t->string('logo_path')->nullable();
            $t->string('alamat')->nullable();
            $t->string('telp')->nullable();
            $t->string('email')->nullable();
            $t->string('website')->nullable();
            $t->string('operation_mode')->default('konvensional'); // konvensional|syariah|dual
            $t->string('mata_uang', 3)->default('IDR');
            $t->integer('tahun_buku')->default(2026);
            $t->string('status')->default('aktif'); // aktif|suspended|terminated
            $t->date('subscription_until')->nullable();
            $t->string('plan')->default('basic');
            $t->json('meta')->nullable();
            $t->softDeletes();
            $t->timestamps();
        });

        Schema::create('settings', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $t->string('group')->default('general');
            $t->string('key');
            $t->text('value')->nullable();
            $t->string('type')->default('string'); // string|int|bool|json|decimal
            $t->timestamps();
            $t->unique(['tenant_id', 'group', 'key']);
        });

        Schema::create('cabang', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $t->string('kode', 10)->unique();
            $t->string('nama');
            $t->string('alamat')->nullable();
            $t->string('telp')->nullable();
            $t->string('tipe')->default('kantor_pusat'); // kantor_pusat|cabang|kantor_kas
            $t->boolean('aktif')->default(true);
            $t->softDeletes();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cabang');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('tenants');
    }
};
