<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Demo credentials — change these before deploying to production.
        User::create([
            'name' => 'Workshop Admin',
            'email' => 'admin@autocarehub.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '0771234567',
        ]);

        User::create([
            'name' => 'Front Desk Staff',
            'email' => 'staff@autocarehub.test',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'phone' => '0777654321',
        ]);

        User::create([
            'name' => 'Kasun Perera',
            'email' => 'mechanic@autocarehub.test',
            'password' => Hash::make('password'),
            'role' => 'mechanic',
            'phone' => '0711122233',
        ]);

        User::create([
            'name' => 'Nimal Silva',
            'email' => 'customer@autocarehub.test',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '0709988776',
            'address' => 'Negombo, Sri Lanka',
        ]);

        User::create([
            'name' => 'Amara Fernando',
            'email' => 'amara@autocarehub.test',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '0765544332',
            'address' => 'Colombo, Sri Lanka',
        ]);
    }
}
