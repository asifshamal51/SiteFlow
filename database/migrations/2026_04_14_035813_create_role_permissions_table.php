<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->id();

            // 🔥 RELATIONS
            $table->foreignId('role_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('permission_id')
                ->constrained()
                ->cascadeOnDelete();

            // 🔥 CONTROL
            $table->boolean('is_active')->default(true);

            // 🔥 AUDIT
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users');

            $table->timestamps();

            // 🔥 SAFETY
            $table->unique(['role_id', 'permission_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('role_permissions');
    }
};
