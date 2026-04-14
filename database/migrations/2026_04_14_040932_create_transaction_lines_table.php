<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaction_lines', function (Blueprint $table) {
            $table->id();

            // 🧾 HEADER LINK
            $table->foreignId('transaction_id')
                ->constrained()
                ->cascadeOnDelete();

            // 📊 ACCOUNT
            $table->foreignId('account_id')->constrained();

            // 🏗️ PROJECT (OPTIONAL BUT STRATEGIC)
            $table->foreignId('project_id')->nullable()->constrained();

            // 💰 ORIGINAL CURRENCY
            $table->decimal('debit_original', 15, 2)->default(0);
            $table->decimal('credit_original', 15, 2)->default(0);

            $table->foreignId('currency_id')->constrained();
            $table->decimal('exchange_rate', 15, 6);

            // 💰 BASE CURRENCY
            $table->decimal('debit_base', 15, 2)->default(0);
            $table->decimal('credit_base', 15, 2)->default(0);

            // 🧠 LINE DESCRIPTION (IMPORTANT FIX)
            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_lines');
    }
};
