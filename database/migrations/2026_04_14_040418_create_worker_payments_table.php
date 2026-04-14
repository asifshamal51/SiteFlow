<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('worker_payments', function (Blueprint $table) {
            $table->id();

            // 👷 RELATIONS
            $table->foreignId('worker_id')->constrained();
            $table->foreignId('project_id')->constrained();
            $table->foreignId('shareholder_id')->nullable()->constrained();

            // 🧠 PAYMENT CLASSIFICATION
            $table->string('role')->nullable();
            $table->string('payment_type')->nullable();
            // salary / advance / bonus / deduction

            // 💳 PAYMENT METHOD (NEW)
            $table->string('payment_method')->nullable();
            // cash / bank / mobile / in-kind

            // 💰 FINANCIAL DATA
            $table->decimal('amount_original', 15, 2);
            $table->foreignId('currency_id')->constrained();
            $table->decimal('exchange_rate', 15, 6);
            $table->decimal('amount_base', 15, 2);

            // 📅 DATE SYSTEM
            $table->date('date');
            $table->string('date_shamsi')->nullable();

            // 🧠 CONTROL (NEW)
            $table->string('status')->default('paid');
            // paid / pending / approved / rejected

            // 👤 AUDIT
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users');

            // 📘 BOOK SYSTEM
            $table->foreignId('book_page_id')->nullable()->constrained();

            // 🧠 DESCRIPTION
            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('worker_payments');
    }
};
