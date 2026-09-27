<?php

namespace Database\Seeders;

use App\Models\SparePart;
use Illuminate\Database\Seeder;

class SparePartSeeder extends Seeder
{
    public function run(): void
    {
        $parts = [
            ['sku' => 'OIL-FLT-001', 'name' => 'Oil Filter', 'category' => 'filters', 'unit' => 'pcs', 'quantity_in_stock' => 40, 'low_stock_threshold' => 10, 'unit_cost' => 450, 'unit_price' => 800],
            ['sku' => 'AIR-FLT-001', 'name' => 'Air Filter', 'category' => 'filters', 'unit' => 'pcs', 'quantity_in_stock' => 30, 'low_stock_threshold' => 8, 'unit_cost' => 600, 'unit_price' => 1100],
            ['sku' => 'BRK-PAD-F01', 'name' => 'Brake Pad Set (Front)', 'category' => 'brakes', 'unit' => 'set', 'quantity_in_stock' => 15, 'low_stock_threshold' => 5, 'unit_cost' => 3200, 'unit_price' => 5500],
            ['sku' => 'BRK-PAD-R01', 'name' => 'Brake Pad Set (Rear)', 'category' => 'brakes', 'unit' => 'set', 'quantity_in_stock' => 12, 'low_stock_threshold' => 5, 'unit_cost' => 2900, 'unit_price' => 5000],
            ['sku' => 'SPK-PLG-001', 'name' => 'Spark Plug', 'category' => 'electrical', 'unit' => 'pcs', 'quantity_in_stock' => 60, 'low_stock_threshold' => 16, 'unit_cost' => 350, 'unit_price' => 650],
            ['sku' => 'LUB-ENG-5W30', 'name' => 'Engine Oil 5W-30', 'category' => 'lubricants', 'unit' => 'litre', 'quantity_in_stock' => 80, 'low_stock_threshold' => 20, 'unit_cost' => 1400, 'unit_price' => 2200],
            ['sku' => 'BAT-12V-001', 'name' => 'Car Battery 12V 45Ah', 'category' => 'electrical', 'unit' => 'pcs', 'quantity_in_stock' => 6, 'low_stock_threshold' => 3, 'unit_cost' => 15000, 'unit_price' => 21000],
            ['sku' => 'TYR-STD-001', 'name' => 'Standard Tyre 175/65R14', 'category' => 'tyres', 'unit' => 'pcs', 'quantity_in_stock' => 4, 'low_stock_threshold' => 4, 'unit_cost' => 9500, 'unit_price' => 13500],
            ['sku' => 'WIP-BLD-001', 'name' => 'Wiper Blade Pair', 'category' => 'accessories', 'unit' => 'set', 'quantity_in_stock' => 20, 'low_stock_threshold' => 6, 'unit_cost' => 900, 'unit_price' => 1600],
            ['sku' => 'COOL-FLD-001', 'name' => 'Engine Coolant 1L', 'category' => 'lubricants', 'unit' => 'litre', 'quantity_in_stock' => 25, 'low_stock_threshold' => 8, 'unit_cost' => 700, 'unit_price' => 1200],
        ];

        foreach ($parts as $part) {
            SparePart::create($part);
        }
    }
}
