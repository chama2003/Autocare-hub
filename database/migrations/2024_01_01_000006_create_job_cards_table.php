<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_cards', function (Blueprint $table) {
            $table->id();
            $table->string('job_card_number')->unique(); // e.g. JC-2026-0001
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->foreignId('appointment_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('mechanic_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('created_by')->constrained('users');
            $table->unsignedInteger('mileage_at_service_km')->nullable();
            $table->text('customer_complaint')->nullable();
            $table->text('diagnosis')->nullable();
            // pending -> in_progress -> quality_check -> completed
            $table->string('status')->default('pending');
            $table->decimal('labor_hours', 6, 2)->default(0);
            $table->decimal('labor_rate_per_hour', 10, 2)->default(0);
            $table->dateTime('opened_at')->useCurrent();
            $table->dateTime('closed_at')->nullable();
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_cards');
    }
};
