<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('funding_transactions', function (Blueprint $table) {
            $table->id();

            // 🏗️ RELATIONS
            $table->foreignId('project_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('shareholder_id')
                ->constrained()
                ->restrictOnDelete();

            // 🔗 SOURCE TRACKING (ERP POLYMORPHIC)
            $table->string('source_type');
            $table->unsignedBigInteger('source_id')->nullable();

            // 💰 FINANCIAL DATA
            $table->decimal('amount_original', 15, 2);

            $table->foreignId('currency_id')
                ->constrained()
                ->restrictOnDelete();

            $table->decimal('exchange_rate', 15, 6);
            $table->decimal('amount_base', 15, 2);

            // 📅 DATES
            $table->date('date');
            $table->string('date_shamsi')->nullable();
            $table->timestamp('recorded_at')->useCurrent();

            // 🧾 BUSINESS INFO
            $table->text('description')->nullable();
            $table->string('reference_no')->nullable();

            // 🧠 STATUS CONTROL (IMPORTANT FIX)
            $table->string('status')->default('approved');
            // pending / approved / rejected / reversed

            // 👤 AUDIT
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users');

            // ⚡ PERFORMANCE
            $table->index(['source_type', 'source_id']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('funding_transactions');
    }
};
