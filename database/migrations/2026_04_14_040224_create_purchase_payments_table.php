<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_payments', function (Blueprint $table) {
            $table->id();

            // 🧾 LINK TO PURCHASE
            $table->foreignId('purchase_id')
                ->constrained()
                ->cascadeOnDelete();

            // 💰 PAYMENT DETAILS
            $table->decimal('amount_original', 15, 2);
            $table->foreignId('currency_id')->constrained();
            $table->decimal('exchange_rate', 15, 6);
            $table->decimal('amount_base', 15, 2);

            // 🧾 PAYMENT CONTROL (NEW)
            $table->string('payment_method')->nullable();
            $table->string('reference_number')->nullable();

            $table->string('status')->default('completed');
            // completed / pending / partial / cancelled

            // 👤 AUDIT
            $table->foreignId('paid_by')
                ->nullable()
                ->constrained('users');

            // 📘 BOOK SYSTEM
            $table->foreignId('book_page_id')
                ->nullable()
                ->constrained();

            // 🏗️ PROJECT
            $table->foreignId('project_id')
                ->constrained();

            // 📅 DATES
            $table->date('date');
            $table->string('date_shamsi')->nullable();

            // 🧠 DESCRIPTION
            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_payments');
    }
};
