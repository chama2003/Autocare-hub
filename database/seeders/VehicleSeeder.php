<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        $nimal = User::where('email', 'customer@autocarehub.test')->first();
        $amara = User::where('email', 'amara@autocarehub.test')->first();

        Vehicle::create([
            'user_id' => $nimal->id,
            'plate_number' => 'CAR-1234',
            'make' => 'Toyota',
            'model' => 'Corolla Axio',
            'type' => 'car',
            'manufacture_year' => 2018,
            'color' => 'White',
            'current_mileage_km' => 62000,
            'service_interval_km' => 5000,
            'service_interval_months' => 6,
            'last_service_date' => now()->subMonths(7),
            'last_service_mileage_km' => 56500,
        ]);

        Vehicle::create([
            'user_id' => $nimal->id,
            'plate_number' => 'BIK-5678',
            'make' => 'Honda',
            'model' => 'CB150R',
            'type' => 'bike',
            'manufacture_year' => 2021,
            'color' => 'Red',
            'current_mileage_km' => 14200,
            'service_interval_km' => 3000,
            'service_interval_months' => 4,
            'last_service_date' => now()->subMonths(2),
            'last_service_mileage_km' => 13000,
        ]);

        Vehicle::create([
            'user_id' => $amara->id,
            'plate_number' => 'CAR-9911',
            'make' => 'Suzuki',
            'model' => 'Wagon R',
            'type' => 'car',
            'manufacture_year' => 2020,
            'color' => 'Silver',
            'current_mileage_km' => 31500,
            'service_interval_km' => 5000,
            'service_interval_months' => 6,
            'last_service_date' => now()->subMonths(1),
            'last_service_mileage_km' => 30000,
        ]);
    }
}
