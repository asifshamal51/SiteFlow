<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_work_logs', function (Blueprint $table) {
            $table->id();

            // 👷 RELATIONS
            $table->foreignId('worker_id')->constrained()->cascadeOnDelete();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();

            // 🛠️ WORK DETAILS
            $table->string('work_type');
            // masonry, painting, electrical, etc.

            $table->decimal('quantity', 15, 2)->nullable();
            $table->string('unit')->nullable();
            // m², m³, pcs, day

            // 📅 DATE
            $table->date('work_date');
            $table->string('date_shamsi')->nullable();

            // 💰 OPTIONAL COST SNAPSHOT (FOR REPORTING)
            $table->decimal('amount_original', 15, 2)->nullable();
            $table->foreignId('currency_id')->nullable()->constrained();
            $table->decimal('exchange_rate', 15, 6)->nullable();
            $table->decimal('amount_base', 15, 2)->nullable();

            // 📘 KHAATA SYSTEM
            $table->foreignId('book_page_id')->nullable()->constrained();

            // 🧠 DESCRIPTION (ONLY ONCE FIXED)
            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_work_logs');
    }
};
