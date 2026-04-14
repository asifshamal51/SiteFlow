<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();

            // 👤 USER
            $table->foreignId('user_id')->constrained();

            // 🏗️ PROJECT CONTEXT (IMPORTANT FIX)
            $table->foreignId('project_id')->nullable()->constrained();

            // 📦 MODULE CONTEXT (VERY USEFUL)
            $table->string('module')->nullable();
            // finance / hr / procurement / land / system

            // 🧾 ACTION TYPE
            $table->string('action');
            // created / updated / deleted / login / approve etc

            // 📊 TARGET RECORD
            $table->string('table_name');
            $table->unsignedBigInteger('record_id');

            // 🧠 CHANGE DATA
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();

            // 🌐 SECURITY INFO
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
