<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('saved_reports')) {
            Schema::create('saved_reports', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('report_key', 60);
                $table->string('name');
                $table->json('params')->nullable();
                $table->boolean('is_favorite')->default(false);
                $table->timestamp('last_run_at')->nullable();
                $table->timestamps();
                $table->unique(['user_id', 'report_key']);
                $table->index('is_favorite');
            });
        }

        if (! Schema::hasTable('report_archives')) {
            Schema::create('report_archives', function (Blueprint $table) {
                $table->id();
                $table->string('report_key', 60);
                $table->string('title');
                $table->string('period')->nullable();
                $table->json('params')->nullable();
                $table->longText('snapshot'); // JSON hasil — immutable
                $table->string('report_version', 20)->default('1.0.0');
                $table->string('checksum', 64);
                $table->string('file_path')->nullable();
                $table->string('status')->default('final'); // final|superseded
                $table->foreignId('generated_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
                $table->index(['report_key', 'period']);
            });
        }

        if (! Schema::hasTable('scheduled_reports')) {
            Schema::create('scheduled_reports', function (Blueprint $table) {
                $table->id();
                $table->string('report_key', 60);
                $table->string('name');
                $table->json('params')->nullable();
                $table->string('frequency'); // daily|weekly|monthly|quarterly|yearly
                $table->string('action')->default('archive'); // archive|email
                $table->string('email_to')->nullable();
                $table->boolean('aktif')->default(true);
                $table->timestamp('last_run_at')->nullable();
                $table->timestamp('next_run_at')->nullable();
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('custom_reports')) {
            Schema::create('custom_reports', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->string('data_source', 60);
                $table->json('columns'); // [{field, aggregate?}]
                $table->json('filters')->nullable(); // [{field, operator, value}]
                $table->json('sort')->nullable(); // {field, dir}
                $table->integer('limit')->default(500);
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('import_batches')) {
            Schema::create('import_batches', function (Blueprint $table) {
                $table->id();
                $table->string('tipe', 40); // members|savings|loans|...
                $table->string('file_name');
                $table->string('file_path')->nullable();
                $table->integer('total_rows')->default(0);
                $table->integer('valid_rows')->default(0);
                $table->integer('invalid_rows')->default(0);
                $table->integer('duplicate_rows')->default(0);
                $table->integer('imported_rows')->default(0);
                $table->string('error_file')->nullable();
                $table->string('status')->default('preview'); // preview|validated|importing|completed|failed
                $table->text('summary')->nullable();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('import_batches');
        Schema::dropIfExists('custom_reports');
        Schema::dropIfExists('scheduled_reports');
        Schema::dropIfExists('report_archives');
        Schema::dropIfExists('saved_reports');
    }
};
