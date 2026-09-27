<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spare_parts', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique();
            $table->string('name');                 // Oil Filter, Brake Pad, Spark Plug...
            $table->string('category')->nullable();  // filters, brakes, electrical, lubricants
            $table->string('unit')->default('pcs');   // pcs, litre, set
            $table->unsignedInteger('quantity_in_stock')->default(0);
            $table->unsignedInteger('low_stock_threshold')->default(5);
            $table->decimal('unit_cost', 10, 2)->default(0);      // purchase cost
            $table->decimal('unit_price', 10, 2)->default(0);     // selling price to customer
            $table->timestamps();

            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spare_parts');
    }
};
