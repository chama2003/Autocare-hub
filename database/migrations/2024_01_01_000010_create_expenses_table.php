<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Operational expenses (e.g. spare parts purchasing costs, utilities, salaries)
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('category'); // spare_parts_purchase, utilities, salary, rent, other
            $table->string('title');
            $table->decimal('amount', 10, 2);
            $table->date('expense_date');
            $table->foreignId('recorded_by')->constrained('users');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['expense_date', 'category']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
