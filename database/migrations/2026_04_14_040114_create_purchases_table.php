<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();

            // 🏗️ PROJECT LINK
            $table->foreignId('project_id')->constrained();

            // 🤝 SUPPLIER (OPTIONAL)
            $table->foreignId('supplier_id')->nullable()->constrained();

            // 📦 CATEGORY (CEMENT, STEEL, ETC)
            $table->foreignId('category_id')->constrained();

            // 🧾 BILL INFO
            $table->string('bill_number')->nullable();

            // 💰 FINANCIAL CORE (IMPORTANT ADDITION)
            $table->decimal('total_amount', 15, 2)->nullable();
            $table->foreignId('currency_id')->nullable()->constrained();
            $table->decimal('exchange_rate', 15, 6)->nullable();
            $table->decimal('amount_base', 15, 2)->nullable();

            // 📘 BOOK SYSTEM
            $table->foreignId('book_page_id')->nullable()->constrained();

            // 📅 DATES
            $table->date('date');
            $table->string('date_shamsi')->nullable();

            // 🧠 DESCRIPTION (FIXED DUPLICATE)
            $table->text('description')->nullable();

            // ⚙️ STATUS CONTROL
            $table->string('status')->default('draft');
            // draft / partial / completed / cancelled

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
