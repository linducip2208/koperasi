<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payment_providers', function (Blueprint $t) {
            $t->id();
            $t->string('nama');
            $t->string('api_format');
            $t->string('base_url');
            $t->string('session_endpoint')->nullable();
            $t->text('api_key_encrypted')->nullable();
            $t->string('merchant_id')->nullable();
            $t->json('extra_headers')->nullable();
            $t->boolean('is_sandbox')->default(true);
            $t->boolean('aktif')->default(true);
            $t->text('catatan')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_providers');
    }
};
