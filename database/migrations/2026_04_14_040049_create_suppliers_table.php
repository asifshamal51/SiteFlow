<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();

            // 🧾 BASIC INFO
            $table->string('name');
            $table->string('company_name')->nullable();

            // 📞 CONTACT INFO
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();

            // 🏗️ ERP CLASSIFICATION
            $table->string('type')->nullable(); // contract, retail, individual

            // ⚙️ STATUS
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
