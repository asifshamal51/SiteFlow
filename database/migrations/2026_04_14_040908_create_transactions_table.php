<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            // 🏗️ PROJECT LINK
            $table->foreignId('project_id')->constrained();

            // 🧾 JOURNAL INFO
            $table->string('reference_number')->unique();
            $table->string('type');
            // expense / income / transfer / adjustment

            // 📅 DATE SYSTEM
            $table->date('date');
            $table->string('date_shamsi')->nullable();

            // 💱 CURRENCY (OPTIONAL BUT IMPORTANT)
            $table->foreignId('currency_id')->nullable()->constrained();

            // 🧠 STATUS CONTROL
            $table->string('status')->default('draft');
            // draft / posted / cancelled

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
        Schema::dropIfExists('transactions');
    }
};
