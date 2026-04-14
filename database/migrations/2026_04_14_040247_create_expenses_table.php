<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();

            // 🏗️ PROJECT LINK
            $table->foreignId('project_id')->constrained();

            // 📦 CLASSIFICATION
            $table->foreignId('category_id')->constrained();

            // 💰 FINANCIAL CORE
            $table->decimal('amount_original', 15, 2);
            $table->foreignId('currency_id')->constrained();
            $table->decimal('exchange_rate', 15, 6);
            $table->decimal('amount_base', 15, 2);

            // 🧠 CONTROL
            $table->string('status')->default('paid');
            // paid / pending / approved / rejected

            $table->string('type')->nullable();

            $table->string('reference_number')->nullable();

            // 👤 AUDIT
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users');

            // 📘 KHAATA SYSTEM
            $table->foreignId('book_page_id')
                ->nullable()
                ->constrained();

            // 🧠 DESCRIPTION
            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
