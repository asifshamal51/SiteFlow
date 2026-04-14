<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();

            // 👷 RELATIONS
            $table->foreignId('worker_id')->constrained();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();

            // 📄 CONTRACT DETAILS
            $table->string('title');
            $table->text('description')->nullable();

            // 💰 FINANCIAL
            $table->decimal('total_amount', 18, 2);

            // 💱 CURRENCY (IMPORTANT FIX)
            $table->foreignId('currency_id')->nullable()->constrained();

            // 📅 TIMELINE
            $table->date('start_date');
            $table->date('end_date')->nullable();

            // 🧠 STATUS CONTROL (IMPORTANT)
            $table->string('status')->default('active');
            // active / completed / cancelled / paused

            // 👤 AUDIT
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
