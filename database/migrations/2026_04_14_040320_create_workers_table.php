<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workers', function (Blueprint $table) {
            $table->id();

            // 🧾 IDENTITY
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('national_id')->nullable();

            // 👷 WORK CLASSIFICATION
            $table->string('type')->nullable();
            // mason, electrician, labor, contractor

            // 💰 BASE WAGE (OPTIONAL BUT USEFUL)
            $table->decimal('daily_wage', 15, 2)->nullable();

            // 📍 ADDRESS
            $table->text('address')->nullable();

            // ⚙️ STATUS
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workers');
    }
};
