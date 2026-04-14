<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('land_funding', function (Blueprint $table) {
            $table->id();

            // 🏗️ RELATIONS
            $table->foreignId('land_transaction_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('shareholder_id')
                ->constrained();

            // 💰 FINANCIAL DATA
            $table->decimal('amount_base', 15, 2);

            $table->foreignId('currency_id')
                ->nullable()
                ->constrained();

            // 🧠 FUNDING TYPE (IMPORTANT ERP ADDITION)
            $table->string('type')->nullable();
            // investment / loan / reimbursement / cost_share

            // 📅 DATE SYSTEM
            $table->date('date')->nullable();
            $table->string('date_shamsi')->nullable();

            // 👤 AUDIT
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users');

            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('land_funding');
    }
};
