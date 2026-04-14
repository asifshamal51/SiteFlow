<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('land_transactions', function (Blueprint $table) {
            $table->id();

            // 🏗️ LAND LINK
            $table->foreignId('project_land_id')
                ->constrained()
                ->cascadeOnDelete();

            // 💰 FINANCIAL DATA
            $table->decimal('amount_original', 15, 2);
            $table->foreignId('currency_id')->constrained();
            $table->decimal('exchange_rate', 15, 6);
            $table->decimal('amount_base', 15, 2);

            // 🔁 TRANSACTION TYPE (VERY IMPORTANT FIX)
            $table->string('type');
            // purchase / investment / maintenance / sale

            // 🔁 FLOW DIRECTION
            $table->string('direction');
            // IN / OUT

            // 📅 DATE SYSTEM
            $table->date('date');
            $table->string('date_shamsi')->nullable();

            // 📘 KHAATA
            $table->foreignId('book_page_id')->nullable()->constrained();

            // 🧠 STATUS CONTROL
            $table->string('status')->default('confirmed');
            // confirmed / pending / cancelled

            // 👤 AUDIT
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users');

            // 🧠 DESCRIPTION
            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('land_transactions');
    }
};
