<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->id();

            // 💱 CURRENCIES
            $table->foreignId('from_currency_id')
                ->constrained('currencies');

            $table->foreignId('to_currency_id')
                ->constrained('currencies');

            // 📊 RATE
            $table->decimal('rate', 15, 6);

            // 📅 DATE SYSTEM
            $table->date('date');
            $table->string('date_shamsi')->nullable();

            // 🧠 SOURCE CONTROL (IMPORTANT IMPROVEMENT)
            $table->string('source')->nullable();
            // manual / bank / api / system

            // 👤 AUDIT
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users');

            $table->timestamps();

            // 🔒 PREVENT DUPLICATES
            $table->unique(['from_currency_id', 'to_currency_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exchange_rates');
    }
};
