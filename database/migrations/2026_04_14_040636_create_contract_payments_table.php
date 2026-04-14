<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contract_payments', function (Blueprint $table) {
            $table->id();

            // 📄 CONTRACT LINK
            $table->foreignId('contract_id')
                ->constrained()
                ->cascadeOnDelete();

            // 🏗️ PROJECT (IMPORTANT FOR REPORTING)
            $table->foreignId('project_id')->nullable()->constrained();

            // 💰 FINANCIAL DATA
            $table->decimal('amount', 18, 2);
            $table->foreignId('currency_id')->constrained();
            $table->decimal('exchange_rate', 15, 6)->default(1);

            // 🧠 PAYMENT TYPE CONTROL
            $table->string('status')->default('paid');
            // paid / pending / partial / cancelled

            $table->string('payment_method')->nullable();
            // cash / bank / mobile / etc

            // 📅 DATE SYSTEM
            $table->date('date_ad');
            $table->string('date_shamsi')->nullable();

            // 👤 AUDIT (VERY IMPORTANT)
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
        Schema::dropIfExists('contract_payments');
    }
};
