<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();

            // 🧾 ACCOUNT INFO
            $table->string('name');
            $table->string('code')->unique(); // IMPORTANT FIX

            // 📊 ACCOUNT CLASSIFICATION
            $table->string('type');
            // asset, liability, equity, expense, income

            // 🌳 HIERARCHY
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('accounts')
                ->nullOnDelete();

            // ⚙️ CONTROL
            $table->boolean('is_active')->default(true);

            // 👤 AUDIT
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
