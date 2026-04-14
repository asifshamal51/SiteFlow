<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();

            // 🔥 ROLE IDENTITY
            $table->string('name')->unique();
            $table->text('description')->nullable();

            // 🔥 CONTROL FLAGS
            $table->boolean('is_system')->default(false); // system protected roles
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
