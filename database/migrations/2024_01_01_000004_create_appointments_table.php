<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();       // customer
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_type_id')->constrained();
            $table->date('appointment_date');
            $table->time('appointment_time');
            $table->text('notes')->nullable();
            // pending -> approved -> completed | rescheduled | cancelled
            $table->string('status')->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['appointment_date', 'appointment_time']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
