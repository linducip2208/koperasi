<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Kode unik per provider — dipakai di URL webhook /webhooks/payment/{kode}.
        // Additive & nullable agar data existing tidak rusak.
        if (! Schema::hasColumn('payment_providers', 'kode')) {
            Schema::table('payment_providers', function (Blueprint $table) {
                $table->string('kode', 50)->nullable()->unique()->after('nama');
            });

            // Backfill: slug dari nama untuk provider yang sudah ada.
            $providers = \Illuminate\Support\Facades\DB::table('payment_providers')->get(['id', 'nama']);
            foreach ($providers as $p) {
                $kode = \Illuminate\Support\Str::slug($p->nama ?: ('provider-'.$p->id), '-');
                $i = 0;
                $candidate = $kode;
                while (\Illuminate\Support\Facades\DB::table('payment_providers')->where('kode', $candidate)->exists()) {
                    $candidate = $kode.'-'.(++$i);
                }
                \Illuminate\Support\Facades\DB::table('payment_providers')->where('id', $p->id)->update(['kode' => $candidate]);
            }
        }

        // Idempotency + audit trail webhook (anti double-entry saat gateway retry).
        if (! Schema::hasTable('webhook_events')) {
            Schema::create('webhook_events', function (Blueprint $table) {
                $table->id();
                $table->foreignId('payment_provider_id')->constrained('payment_providers')->cascadeOnDelete();
                $table->string('payment_id', 100);
                $table->string('order_id', 100)->nullable();
                $table->bigInteger('amount')->default(0);
                $table->string('status', 30)->default('received');
                $table->json('raw')->nullable();
                $table->timestamp('processed_at')->nullable();
                $table->timestamps();
                $table->unique(['payment_provider_id', 'payment_id']);
                $table->index('order_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('webhook_events');
        if (Schema::hasColumn('payment_providers', 'kode')) {
            Schema::table('payment_providers', function (Blueprint $table) {
                $table->dropUnique(['kode']);
                $table->dropColumn('kode');
            });
        }
    }
};
