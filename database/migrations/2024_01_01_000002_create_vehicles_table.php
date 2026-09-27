<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('plate_number')->unique();
            $table->string('make');
            $table->string('model');
            $table->string('type')->default('car');
            $table->unsignedSmallInteger('manufacture_year');
            $table->string('color')->nullable();
            $table->unsignedInteger('current_mileage_km')->default(0);
            $table->unsignedInteger('service_interval_km')->default(5000);
            $table->unsignedSmallInteger('service_interval_months')->default(6);
            $table->date('last_service_date')->nullable();
            $table->unsignedInteger('last_service_mileage_km')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'plate_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
