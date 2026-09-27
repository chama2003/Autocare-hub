@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
@auth
@if(auth()->user()->isCustomer())
    {{-- ===================== CUSTOMER DASHBOARD ===================== --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-5">
            <p class="text-sm text-slate-500">My Vehicles</p>
            <p class="text-3xl font-bold text-brand-700">{{ $vehicles->count() }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5">
            <p class="text-sm text-slate-500">Upcoming Appointments</p>
            <p class="text-3xl font-bold text-brand-700">{{ $upcomingAppointments->count() }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5">
            <p class="text-sm text-slate-500">Vehicles Due For Service</p>
            <p class="text-3xl font-bold {{ $dueForService->count() ? 'text-red-600' : 'text-emerald-600' }}">{{ $dueForService->count() }}</p>
        </div>
    </div>

    @if($dueForService->count())
    <div class="bg-amber-50 border border-amber-200 text-amber-800 rounded-xl p-4 mb-6 text-sm">
        <i class="fa-solid fa-triangle-exclamation mr-2"></i>
        The following vehicles are due for their next service: {{ $dueForService->pluck('plate_number')->join(', ') }}.
        <a href="{{ route('appointments.create') }}" class="underline font-medium">Book now</a>.
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm p-5 mb-6">
        <h2 class="font-semibold mb-4">My Vehicles</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($vehicles as $vehicle)
            <a href="{{ route('vehicles.show', $vehicle) }}" class="border rounded-lg p-4 hover:border-brand-500 transition">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="font-semibold">{{ $vehicle->plate_number }}</p>
                        <p class="text-sm text-slate-500">{{ $vehicle->make }} {{ $vehicle->model }} ({{ $vehicle->manufacture_year }})</p>
                    </div>
                    <i class="fa-solid {{ $vehicle->type === 'bike' ? 'fa-motorcycle' : 'fa-car' }} text-brand-500"></i>
                </div>
                <p class="text-xs text-slate-400 mt-2">{{ number_format($vehicle->current_mileage_km) }} km &middot; {{ $vehicle->job_cards_count }} service(s)</p>
                @if($vehicle->isServiceDue())
                    <span class="inline-block mt-2 text-xs px-2 py-0.5 rounded-full bg-red-100 text-red-700">Service Due</span>
                @endif
            </a>
            @empty
            <p class="text-slate-500 text-sm">No vehicles registered yet. <a href="{{ route('vehicles.create') }}" class="text-brand-600 underline">Add one</a>.</p>
            @endforelse
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-5">
        <h2 class="font-semibold mb-4">Upcoming Appointments</h2>
        <table class="w-full text-sm">
            <thead class="text-left text-slate-500 border-b">
                <tr><th class="py-2">Date</th><th>Time</th><th>Vehicle</th><th>Service</th><th>Status</th></tr>
            </thead>
            <tbody>
                @forelse($upcomingAppointments as $appt)
                <tr class="border-b last:border-0">
                    <td class="py-2">{{ $appt->appointment_date->format('d M Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($appt->appointment_time)->format('h:i A') }}</td>
                    <td>{{ $appt->vehicle->plate_number }}</td>
                    <td>{{ $appt->serviceType->name }}</td>
                    <td><span class="px-2 py-0.5 rounded-full text-xs bg-brand-100 text-brand-700 capitalize">{{ $appt->status }}</span></td>
                </tr>
                @empty
                <tr><td colspan="5" class="py-4 text-center text-slate-400">No upcoming appointments.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

@else
    {{-- ===================== STAFF / ADMIN / MECHANIC DASHBOARD ===================== --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-5">
            <p class="text-sm text-slate-500">Today's Appointments</p>
            <p class="text-3xl font-bold text-brand-700">{{ $todaysAppointments->count() }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5">
            <p class="text-sm text-slate-500">Open Job Cards</p>
            <p class="text-3xl font-bold text-brand-700">{{ $openJobCards->count() }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5">
            <p class="text-sm text-slate-500">Low Stock Items</p>
            <p class="text-3xl font-bold {{ $lowStockParts->count() ? 'text-red-600' : 'text-emerald-600' }}">{{ $lowStockParts->count() }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5">
            <p class="text-sm text-slate-500">Revenue (This Month)</p>
            <p class="text-3xl font-bold text-emerald-700">Rs. {{ number_format($monthRevenue, 2) }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-5">
            <h2 class="font-semibold mb-4">Today's Appointments</h2>
            <div class="space-y-2 max-h-96 overflow-y-auto">
                @forelse($todaysAppointments as $appt)
                <div class="flex justify-between items-center border rounded-lg px-3 py-2 text-sm">
                    <div>
                        <p class="font-medium">{{ $appt->vehicle->plate_number }} &middot; {{ $appt->serviceType->name }}</p>
                        <p class="text-slate-400 text-xs">{{ $appt->customer->name }} &middot; {{ \Carbon\Carbon::parse($appt->appointment_time)->format('h:i A') }}</p>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-xs bg-brand-100 text-brand-700 capitalize">{{ $appt->status }}</span>
                </div>
                @empty
                <p class="text-slate-400 text-sm">Nothing scheduled for today.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-5">
            <h2 class="font-semibold mb-4">Open Job Cards</h2>
            <div class="space-y-2 max-h-96 overflow-y-auto">
                @forelse($openJobCards as $jc)
                <a href="{{ route('jobcards.show', $jc) }}" class="flex justify-between items-center border rounded-lg px-3 py-2 text-sm hover:border-brand-500">
                    <div>
                        <p class="font-medium">{{ $jc->job_card_number }} &middot; {{ $jc->vehicle->plate_number }}</p>
                        <p class="text-slate-400 text-xs">Mechanic: {{ $jc->mechanic?->name ?? 'Unassigned' }}</p>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-xs bg-amber-100 text-amber-700 capitalize">{{ str_replace('_',' ',$jc->status) }}</span>
                </a>
                @empty
                <p class="text-slate-400 text-sm">No open job cards.</p>
                @endforelse
            </div>
        </div>
    </div>

    @if($lowStockParts->count())
    <div class="bg-white rounded-xl shadow-sm p-5 mt-6">
        <h2 class="font-semibold mb-4 text-red-600"><i class="fa-solid fa-triangle-exclamation mr-1"></i> Low Stock Alerts</h2>
        <div class="flex flex-wrap gap-2">
            @foreach($lowStockParts as $part)
            <span class="text-xs px-3 py-1 rounded-full bg-red-100 text-red-700">{{ $part->name }} — {{ $part->quantity_in_stock }} {{ $part->unit }} left</span>
            @endforeach
        </div>
    </div>
    @endif
@endif
@endauth
@endsection
