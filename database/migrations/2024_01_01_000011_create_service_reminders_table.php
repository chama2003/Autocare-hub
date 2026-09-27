<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->string('trigger_type'); // mileage, time
            $table->date('due_date')->nullable();
            $table->unsignedInteger('due_mileage_km')->nullable();
            $table->boolean('notified')->default(false);
            $table->dateTime('notified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_reminders');
    }
};
