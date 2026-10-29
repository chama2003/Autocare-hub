<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\ServiceType;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class JobCardWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_approved_appointments_create_active_job_cards_for_mechanics(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@autocarehub.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $customer = User::create([
            'name' => 'Customer User',
            'email' => 'customer@autocarehub.test',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);

        $mechanic = User::create([
            'name' => 'Mechanic User',
            'email' => 'mechanic@autocarehub.test',
            'password' => Hash::make('password'),
            'role' => 'mechanic',
        ]);

        $vehicle = Vehicle::create([
            'user_id' => $customer->id,
            'plate_number' => 'ABC-123',
            'make' => 'Toyota',
            'model' => 'Corolla',
            'type' => 'Sedan',
            'manufacture_year' => 2022,
            'color' => 'White',
            'current_mileage_km' => 15000,
            'service_interval_km' => 5000,
            'service_interval_months' => 6,
        ]);

        $serviceType = ServiceType::create([
            'name' => 'Oil Change',
            'description' => 'Engine oil replacement',
        ]);

        $appointment = Appointment::create([
            'user_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'service_type_id' => $serviceType->id,
            'appointment_date' => now()->addDay()->toDateString(),
            'appointment_time' => '09:00:00',
            'notes' => 'Needs oil change',
            'status' => 'pending',
        ]);

        $this->actingAs($admin, 'web')
            ->post(route('appointments.approve', $appointment));

        $this->assertDatabaseHas('job_cards', [
            'appointment_id' => $appointment->id,
            'vehicle_id' => $vehicle->id,
            'mechanic_id' => $mechanic->id,
            'status' => 'pending',
        ]);
    }

    public function test_job_card_show_page_handles_missing_vehicle_gracefully(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);

        $vehicle = Vehicle::create([
            'user_id' => $staff->id,
            'plate_number' => 'BAH-909',
            'make' => 'Honda',
            'model' => 'Civic',
            'type' => 'Sedan',
            'manufacture_year' => 2021,
            'color' => 'Black',
            'current_mileage_km' => 32000,
            'service_interval_km' => 4500,
            'service_interval_months' => 6,
        ]);

        $jobCard = \App\Models\JobCard::create([
            'job_card_number' => 'JC-2026-0002',
            'vehicle_id' => $vehicle->id,
            'appointment_id' => null,
            'mechanic_id' => $staff->id,
            'created_by' => $staff->id,
            'mileage_at_service_km' => 25000,
            'customer_complaint' => 'Engine noise',
            'status' => 'pending',
            'labor_rate_per_hour' => 1500,
            'opened_at' => now(),
        ]);

        $jobCard->setRelation('vehicle', null);

        $html = view('jobcards.show', [
            'jobCard' => $jobCard,
            'serviceTypes' => collect(),
            'spareParts' => collect(),
        ])->render();

        $this->assertStringContainsString('Vehicle not assigned', $html);
    }
}
