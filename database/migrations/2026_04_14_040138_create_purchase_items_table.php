<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_items', function (Blueprint $table) {
            $table->id();

            // 🧾 LINK TO PURCHASE
            $table->foreignId('purchase_id')
                ->constrained()
                ->cascadeOnDelete();

            // 📦 MATERIAL INFO
            $table->string('material_name');

            // 📊 QUANTITY DETAILS
            $table->decimal('ordered_quantity', 15, 2);
            $table->decimal('received_quantity', 15, 2)->default(0);

            $table->string('unit');

            // 💰 PRICING
            $table->decimal('unit_price', 15, 2)->nullable();
            $table->decimal('total_price', 15, 2)->nullable();

            // 🧠 ERP OPTIONAL LINK (IMPROVES REPORTING)
            $table->foreignId('category_id')->nullable()->constrained();

            // 📘 BOOK SYSTEM (OPTIONAL FOR FUTURE TRACEABILITY)
            $table->foreignId('book_page_id')->nullable()->constrained();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_items');
    }
};
