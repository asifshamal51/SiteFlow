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

            // 🏗️ PROJECT LINK
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();

            // 📍 LAND DETAILS
            $table->text('description')->nullable();

            $table->decimal('area', 15, 2)->nullable();
            $table->string('area_unit')->nullable();
            // sqm, jerib, etc.

            // 💰 FINANCIAL VALUE
            $table->decimal('purchase_price', 18, 2)->nullable();
            $table->foreignId('currency_id')->nullable()->constrained();
            $table->decimal('exchange_rate', 15, 6)->nullable();
            $table->decimal('price_base', 18, 2)->nullable();

            // 📅 DATE
            $table->date('purchase_date')->nullable();
            $table->string('date_shamsi')->nullable();

            // 🧠 STATUS CONTROL
            $table->string('status')->default('owned');
            // owned / pending / sold / reserved

            // 💡 COST CONTROL
            $table->boolean('include_in_project_cost')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_land');
    }
};
