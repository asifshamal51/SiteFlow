<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('goods_receipts', function (Blueprint $table) {
            $table->id();

            // 📦 LINK TO PURCHASE ITEM
            $table->foreignId('purchase_item_id')
                ->constrained()
                ->cascadeOnDelete();

            // 📊 DELIVERY INFO
            $table->decimal('received_quantity', 15, 2);

            // 🧾 OPTIONAL TRACEABILITY
            $table->foreignId('supplier_id')
                ->nullable()
                ->constrained();

            $table->foreignId('received_by')
                ->nullable()
                ->constrained('users');

            // 📦 UNIT (OPTIONAL BUT USEFUL)
            $table->string('unit')->nullable();

            // 📘 KHAATA SYSTEM
            $table->foreignId('book_page_id')
                ->nullable()
                ->constrained();

            // 🏗️ PROJECT LINK
            $table->foreignId('project_id')
                ->constrained();

            // 📅 DATES
            $table->date('date');
            $table->string('date_shamsi')->nullable();

            // 🧠 DESCRIPTION
            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('goods_receipts');
    }
};
