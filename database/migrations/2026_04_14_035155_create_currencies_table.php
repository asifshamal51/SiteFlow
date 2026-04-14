<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique();// AFN, USD
            $table->string('name');
            $table->string('symbol')->nullable();
            $table->boolean('is_base')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('currencies');
    }
};
