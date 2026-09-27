<?php

namespace Database\Seeders;

use App\Models\ServiceType;
use Illuminate\Database\Seeder;

class ServiceTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'Oil Change', 'description' => 'Engine oil and filter replacement', 'base_labor_cost' => 1500, 'estimated_minutes' => 30],
            ['name' => 'General Tuning', 'description' => 'Full engine tune-up and inspection', 'base_labor_cost' => 3500, 'estimated_minutes' => 90],
            ['name' => 'Engine Diagnostics', 'description' => 'Computerised diagnostic scan', 'base_labor_cost' => 2500, 'estimated_minutes' => 45],
            ['name' => 'Brake Service', 'description' => 'Brake pad/disc inspection and replacement', 'base_labor_cost' => 3000, 'estimated_minutes' => 60],
            ['name' => 'Mechanical Repair', 'description' => 'General mechanical repair work', 'base_labor_cost' => 4000, 'estimated_minutes' => 120],
            ['name' => 'Tyre Replacement & Alignment', 'description' => 'Tyre change with wheel alignment', 'base_labor_cost' => 2000, 'estimated_minutes' => 45],
            ['name' => 'Battery Check & Replacement', 'description' => 'Battery health check and swap', 'base_labor_cost' => 1000, 'estimated_minutes' => 20],
            ['name' => 'Full Service (Major)', 'description' => 'Comprehensive multi-point major service', 'base_labor_cost' => 6000, 'estimated_minutes' => 180],
        ];

        foreach ($types as $type) {
            ServiceType::create($type);
        }
    }
}
