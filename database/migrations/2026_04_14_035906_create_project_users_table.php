<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_users', function (Blueprint $table) {
            $table->id();

            // 🔗 RELATIONS
            $table->foreignId('project_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // 🔥 ROLE CONTEXT (VERY IMPORTANT FOR ERP)
            $table->foreignId('role_id')
                ->nullable()
                ->constrained();

            // 👑 ADMIN TRACEABILITY
            $table->foreignId('assigned_by')
                ->nullable()
                ->constrained('users');

            // 🔥 CONTROL
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            // 🔒 SAFETY RULE
            $table->unique(['project_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_users');
    }
};
