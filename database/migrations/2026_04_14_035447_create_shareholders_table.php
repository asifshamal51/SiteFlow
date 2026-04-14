<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('shareholders', function (Blueprint $table) {
            $table->id();

            // 👤 BASIC INFO
            $table->string('name');

            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();

            // 🧾 IDENTIFICATION (IMPORTANT ERP ADDITION)
            $table->string('national_id')->nullable();
            $table->string('code')->unique(); // investor code

            // 🧠 STATUS CONTROL
            $table->string('status')->default('active');
            // active / inactive / suspended

            // 👤 AUDIT
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('shareholders');
    }
};
