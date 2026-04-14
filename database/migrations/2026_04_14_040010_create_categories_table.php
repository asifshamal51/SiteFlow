<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();

            // 🧾 BASIC INFO
            $table->string('name');

            // 📦 TYPE CLASSIFICATION
            $table->string('type'); // material / expense

            // 🧠 ERP ENHANCEMENTS
            $table->string('group')->nullable(); // fuel, material, labor, etc
            $table->string('default_unit')->nullable(); // ton, liter, pcs
            $table->text('description')->nullable();

            // ⚙️ CONTROL FLAGS
            $table->boolean('is_system')->default(false);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
