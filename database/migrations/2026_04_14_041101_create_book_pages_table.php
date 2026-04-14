<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('book_pages', function (Blueprint $table) {
            $table->id();

            // 🏗️ PROJECT LINK
            $table->foreignId('project_id')
                ->constrained()
                ->cascadeOnDelete();

            // 📘 LEDGER INFO
            $table->integer('page_no');

            $table->string('title');
            // Cement, Salary, Fuel, Income, etc

            $table->string('type')->nullable();
            // expense / income / asset / liability

            // 🧠 STATUS CONTROL
            $table->string('status')->default('active');
            // active / locked / archived

            // 📘 DESCRIPTION
            $table->text('description')->nullable();

            // 👤 AUDIT
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users');

            $table->unique(['project_id', 'page_no']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('book_pages');
    }
};
