<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();

            // 🏗️ BASIC INFO
            $table->string('name');
            $table->string('code')->unique();

            $table->string('location')->nullable();
            $table->text('description')->nullable();

            // 📊 CLASSIFICATION
            $table->string('type')->nullable();
            // residential / commercial / infrastructure

            // 📅 TIMELINE
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            $table->string('start_date_shamsi')->nullable();
            $table->string('end_date_shamsi')->nullable();

            // 🧠 STATUS (KEEP ONLY THIS - REMOVE is_active IN LOGIC)
            $table->enum('status', ['active', 'completed', 'on_hold'])
                ->default('active');

            // 💱 FINANCIAL BASE
            $table->foreignId('currency_id')
                ->nullable()
                ->constrained();

            $table->decimal('initial_budget', 18, 2)->nullable();

            // 📈 PROGRESS
            $table->decimal('progress_percent', 5, 2)->default(0);

            // 👤 AUDIT
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
