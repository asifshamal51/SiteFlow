<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('worker_advances', function (Blueprint $table) {
            $table->id();

            // 👷 RELATIONS
            $table->foreignId('worker_id')->constrained();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();

            // 💰 FINANCIAL DATA
            $table->decimal('amount', 18, 2);
            $table->decimal('remaining', 18, 2)->default(0);

            // 💱 CURRENCY SUPPORT (IMPORTANT FIX)
            $table->foreignId('currency_id')->nullable()->constrained();

            // 📅 DATE SYSTEM
            $table->date('date');
            $table->string('date_shamsi')->nullable();

            // 📘 KHAATA INTEGRATION
            $table->foreignId('book_page_id')->nullable()->constrained();

            // 👤 AUDIT (VERY IMPORTANT)
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users');

            // 🧠 STATUS CONTROL
            $table->string('status')->default('active');
            // active / partial / cleared / cancelled

            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('worker_advances');
    }
};
