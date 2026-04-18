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

            $table->foreignId('worker_id')->constrained();
            $table->foreignId('project_id')->constrained();

            // ⭐ IMPORTANT: SHAREHOLDER LINK
            $table->foreignId('shareholder_id')->constrained();

            // payment classification
            $table->string('worker_type')->nullable();
            // daily / contract / office

            $table->string('payment_type')->nullable();
            // salary / advance / bonus / deduction

            $table->decimal('amount_original', 15, 2);
            $table->foreignId('currency_id')->constrained();
            $table->decimal('exchange_rate', 15, 6)->default(1);
            $table->decimal('amount_base', 15, 2);

            // BOOK PAGE
            $table->integer('book_page_no')->nullable();

            $table->date('date');
            $table->string('date_shamsi')->nullable();

            $table->string('payment_method')->nullable();

            $table->text('description')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('worker_payments');
    }
};
