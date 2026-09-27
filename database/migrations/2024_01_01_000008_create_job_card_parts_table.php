<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Spare parts consumed within a job card (triggers stock deduction)
    public function up(): void
    {
        Schema::create('job_card_parts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_card_id')->constrained()->cascadeOnDelete();
            $table->foreignId('spare_part_id')->constrained();
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('unit_price', 10, 2)->default(0); // price at time of use
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_card_parts');
    }
};
