<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cost_center_entries', function (Blueprint $table) {
            $table->id();

            // 🏗️ PROJECT
            $table->foreignId('project_id')->constrained();

            // 🔗 SOURCE (POLYMORPHIC STYLE)
            $table->string('source_type');
            $table->unsignedBigInteger('source_id');

            // 💰 AMOUNT
            $table->decimal('amount_base', 15, 2);

            // 🔁 FLOW TYPE (VERY IMPORTANT FOR PROFIT/LOSS)
            $table->string('direction');
            // IN = income (funding)
            // OUT = expense (purchase, salary)

            // 📦 CLASSIFICATION
            $table->string('type')->nullable();
            // purchase / expense / salary / funding / advance

            // 📘 KHAATA SYSTEM
            $table->foreignId('book_page_id')->nullable()->constrained();

            // 📅 DATE FOR REPORTING
            $table->date('date')->nullable();
            $table->string('date_shamsi')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cost_center_entries');
    }
};
