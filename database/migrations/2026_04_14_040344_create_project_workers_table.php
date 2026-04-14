<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_workers', function (Blueprint $table) {
            $table->id();

            // 🏗️ RELATIONS
            $table->foreignId('project_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('worker_id')
                ->constrained()
                ->cascadeOnDelete();

            // 🧾 ROLE IN PROJECT
            $table->string('role')->nullable();

            // 🧠 STATUS (FIXED NAMING)
            $table->boolean('is_active')->default(true);

            // 📅 TIMELINE (IMPORTANT ERP IMPROVEMENT)
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            $table->string('start_date_shamsi')->nullable();
            $table->string('end_date_shamsi')->nullable();

            $table->timestamps();

            // 🔒 PREVENT DUPLICATES (VERY IMPORTANT)
            $table->unique(['project_id', 'worker_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_workers');
    }
};
