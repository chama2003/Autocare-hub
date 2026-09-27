@extends('layouts.app')
@section('title', 'Vehicles')
@section('page-title', 'Vehicles')

@section('content')
<div class="flex justify-between items-center mb-5">
    <p class="text-sm text-slate-500">Manage registered vehicles and their service history.</p>
    <a href="{{ route('vehicles.create') }}" class="bg-brand-600 hover:bg-brand-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
        <i class="fa-solid fa-plus mr-1"></i> Add Vehicle
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 text-left text-slate-500">
            <tr>
                <th class="px-4 py-3">Plate No.</th>
                <th class="px-4 py-3">Make / Model</th>
                <th class="px-4 py-3">Year</th>
                <th class="px-4 py-3">Mileage</th>
                @if(!auth()->user()->isCustomer())<th class="px-4 py-3">Owner</th>@endif
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($vehicles as $vehicle)
            <tr class="border-t hover:bg-slate-50">
                <td class="px-4 py-3 font-medium">{{ $vehicle->plate_number }}</td>
                <td class="px-4 py-3">{{ $vehicle->make }} {{ $vehicle->model }}</td>
                <td class="px-4 py-3">{{ $vehicle->manufacture_year }}</td>
                <td class="px-4 py-3">{{ number_format($vehicle->current_mileage_km) }} km</td>
                @if(!auth()->user()->isCustomer())<td class="px-4 py-3">{{ $vehicle->owner->name }}</td>@endif
                <td class="px-4 py-3">
                    @if($vehicle->isServiceDue())
                        <span class="px-2 py-0.5 rounded-full text-xs bg-red-100 text-red-700">Service Due</span>
                    @else
                        <span class="px-2 py-0.5 rounded-full text-xs bg-emerald-100 text-emerald-700">Up to date</span>
                    @endif
                </td>
                <td class="px-4 py-3 text-right">
                    <a href="{{ route('vehicles.show', $vehicle) }}" class="text-brand-600 hover:underline">View</a>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-4 py-8 text-center text-slate-400">No vehicles found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@if(method_exists($vehicles, 'links'))
<div class="mt-4">{{ $vehicles->links() }}</div>
@endif
@endsection
