<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_roles', function (Blueprint $table) {
            $table->id();

            // 🔗 RELATIONS
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('role_id')
                ->constrained()
                ->cascadeOnDelete();

            // 🏗️ PROJECT CONTEXT (ERP IMPORTANT)
            $table->foreignId('project_id')
                ->nullable()
                ->constrained();

            // 🔥 CONTROL + AUDIT
            $table->foreignId('assigned_by')
                ->nullable()
                ->constrained('users');

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            // 🔒 SAFETY RULE
            $table->unique(['user_id', 'role_id', 'project_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_roles');
    }
};
