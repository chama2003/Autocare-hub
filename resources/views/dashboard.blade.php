@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    @php
        $user = auth()->user();
    @endphp

    @if($user && $user->isCustomer())
        <div class="mb-6 rounded-[30px] bg-gradient-to-r from-indigo-600 via-violet-600 to-fuchsia-500 p-6 text-white shadow-soft">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-indigo-100">Customer overview</p>
                    <h2 class="mt-2 text-3xl font-bold tracking-tight">Welcome back, {{ $user->name }}.</h2>
                </div>
                <a href="{{ route('vehicles.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-white/10 px-4 py-2.5 text-sm font-semibold text-white ring-1 ring-white/20 hover:bg-white/15">
                    <i class="fa-solid fa-car-side"></i> Manage vehicles
                </a>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            <x-stat-card icon="fa-solid fa-car-side" label="Vehicles" value="{{ $vehicles->count() }}" tone="primary" />
            <x-stat-card icon="fa-solid fa-calendar-day" label="Upcoming Appointments" value="{{ $upcomingAppointments->count() }}" tone="warning" />
            <x-stat-card icon="fa-solid fa-triangle-exclamation" label="Due for Service" value="{{ $dueForService->count() }}" tone="danger" />
        </div>

        <div class="mt-6 grid gap-6 xl:grid-cols-[1.15fr_0.85fr]">
            <x-card>
                <div class="mb-5 flex items-center justify-between">
                    <h3 class="text-lg font-semibold">My vehicles</h3>
                    <a href="{{ route('vehicles.index') }}" class="text-sm font-medium text-violet-600 dark:text-violet-300">View all</a>
                </div>
                <div class="space-y-3">
                    @forelse($vehicles as $vehicle)
                        <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-950/40">
                            <div>
                                <p class="font-semibold text-slate-900 dark:text-white">{{ $vehicle->make }} {{ $vehicle->model }}</p>
                                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $vehicle->plate_number }} • {{ number_format($vehicle->current_mileage_km) }} km</p>
                            </div>
                            <x-badge :variant="$vehicle->isServiceDue() ? 'danger' : 'success'">{{ $vehicle->isServiceDue() ? 'Service due' : 'On track' }}</x-badge>
                        </div>
                    @empty
                        <x-empty-state title="No vehicles yet" message="Register your first vehicle to start tracking service history." />
                    @endforelse
                </div>
            </x-card>

            <x-card>
                <div class="mb-5 flex items-center justify-between">
                    <h3 class="text-lg font-semibold">Upcoming bookings</h3>
                    <a href="{{ route('appointments.index') }}" class="text-sm font-medium text-violet-600 dark:text-violet-300">Book service</a>
                </div>
                <div class="space-y-3">
                    @forelse($upcomingAppointments as $appointment)
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-950/40">
                            <div class="flex items-center justify-between gap-3">
                                <p class="font-semibold text-slate-900 dark:text-white">{{ $appointment->serviceType->name }}</p>
                                <x-badge variant="primary">{{ $appointment->status }}</x-badge>
                            </div>
                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">{{ $appointment->vehicle->plate_number }} • {{ $appointment->appointment_date->format('d M Y') }}</p>
                        </div>
                    @empty
                        <x-empty-state title="No upcoming appointments" message="Your next service visit is waiting to be scheduled." />
                    @endforelse
                </div>
            </x-card>
        </div>
    @else
        <div class="mb-6 rounded-[30px] bg-gradient-to-r from-indigo-600 via-violet-600 to-fuchsia-500 p-6 text-white shadow-soft">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-indigo-100">Operations overview</p>
                    <h2 class="mt-2 text-3xl font-bold tracking-tight">Workshop pulse is running smoothly.</h2>
                </div>
                <a href="{{ route('jobcards.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-white/10 px-4 py-2.5 text-sm font-semibold text-white ring-1 ring-white/20 hover:bg-white/15">
                    <i class="fa-solid fa-clipboard-list"></i> View job cards
                </a>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <x-stat-card icon="fa-solid fa-calendar-day" label="Today's Appointments" value="{{ $todaysAppointments->count() }}" tone="primary" />
            <x-stat-card icon="fa-solid fa-clipboard-list" label="Open Job Cards" value="{{ $openJobCards->count() }}" tone="warning" />
            <x-stat-card icon="fa-solid fa-boxes-stacked" label="Low Stock Items" value="{{ $lowStockParts->count() }}" tone="danger" />
            <x-stat-card icon="fa-solid fa-dollar-sign" label="Month Revenue" value="Rs. {{ number_format($monthRevenue ?? 0, 2) }}" tone="success" />
        </div>

        <div class="mt-6 grid gap-6 xl:grid-cols-2">
            <x-card>
                <div class="mb-5 flex items-center justify-between">
                    <h3 class="text-lg font-semibold">Today’s service queue</h3>
                    <a href="{{ route('appointments.index') }}" class="text-sm font-medium text-violet-600 dark:text-violet-300">Open calendar</a>
                </div>
                <div class="space-y-3">
                    @forelse($todaysAppointments as $appointment)
                        <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-950/40">
                            <div>
                                <p class="font-semibold text-slate-900 dark:text-white">{{ $appointment->vehicle->plate_number }}</p>
                                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $appointment->serviceType->name }} • {{ $appointment->customer->name }}</p>
                            </div>
                            <x-badge variant="primary">{{ $appointment->status }}</x-badge>
                        </div>
                    @empty
                        <x-empty-state title="No appointments today" message="The workshop is clear for the current day." />
                    @endforelse
                </div>
            </x-card>

            <x-card>
                <div class="mb-5 flex items-center justify-between">
                    <h3 class="text-lg font-semibold">Active job cards</h3>
                    <a href="{{ route('jobcards.index') }}" class="text-sm font-medium text-violet-600 dark:text-violet-300">Manage</a>
                </div>
                <div class="space-y-3">
                    @forelse($openJobCards as $jobCard)
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-950/40">
                            <div class="flex items-center justify-between gap-3">
                                <p class="font-semibold text-slate-900 dark:text-white">{{ $jobCard->job_card_number }}</p>
                                <x-badge :variant="match($jobCard->status) { 'pending' => 'warning', 'in_progress' => 'info', 'quality_check' => 'primary', 'completed' => 'success', default => 'default' }">{{ str_replace('_', ' ', $jobCard->status) }}</x-badge>
                            </div>
                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">{{ $jobCard->vehicle->plate_number }} • {{ $jobCard->mechanic?->name ?? 'Unassigned' }}</p>
                        </div>
                    @empty
                        <x-empty-state title="No active jobs" message="There are no workshop jobs in progress right now." />
                    @endforelse
                </div>
            </x-card>
        </div>
    @endif
@endsection
