<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_land', function (Blueprint $table) {
            $table->id();

            // 🏗️ PROJECT IT BELONGS TO
            $table->foreignId('project_id')
                ->constrained()
                ->cascadeOnDelete();

            // 👤 SHAREHOLDER WHO PAID
            $table->foreignId('shareholder_id')
                ->constrained()
                ->restrictOnDelete();

            // 🌍 LAND DETAILS
            $table->string('name'); // e.g. "Kabul Plot A"
            $table->text('description')->nullable();

            // 💰 PURCHASE AMOUNT (SIMPLE)
            $table->decimal('purchase_amount', 18, 2);

            $table->foreignId('currency_id')
                ->constrained()
                ->restrictOnDelete();

            $table->decimal('exchange_rate', 15, 6)->default(1);
            $table->decimal('amount_base', 18, 2);

            // 📘 SIMPLE BOOK PAGE NUMBER
            $table->integer('book_page_no')->nullable();

            // 📝 OPTIONAL NOTES
            $table->text('note')->nullable();

            // 📅 PURCHASE DATE
            $table->date('purchase_date')->nullable();
            $table->string('date_shamsi')->nullable();

            // ⚙️ STATUS
            $table->string('status')->default('owned');
            // owned / sold / reserved

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_land');
    }
};
