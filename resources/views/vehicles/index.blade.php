@extends('layouts.app')
@section('title', 'Vehicles')
@section('page-title', 'Vehicles')

@section('content')
<div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
    <p class="text-sm text-slate-500 dark:text-slate-400">Manage registered vehicles and their service history.</p>
    <a href="{{ route('vehicles.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 to-violet-500 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-violet-500/20 hover:from-indigo-400 hover:to-violet-400">
        <i class="fa-solid fa-plus"></i> Add Vehicle
    </a>
</div>

<div class="overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-soft dark:border-slate-800 dark:bg-slate-900">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-left text-slate-500 dark:bg-slate-950/80 dark:text-slate-400">
                <tr>
                    <th class="px-4 py-3">Plate No.</th>
                    <th class="px-4 py-3">Make / Model</th>
                    <th class="px-4 py-3">Year</th>
                    <th class="px-4 py-3">Mileage</th>
                    @if(!auth()->user()->isCustomer())<th class="px-4 py-3">Owner</th>@endif
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($vehicles as $vehicle)
                <tr class="border-t border-slate-200 text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:text-slate-200 dark:hover:bg-slate-950/50">
                    <td class="px-4 py-3 font-semibold">{{ $vehicle->plate_number }}</td>
                    <td class="px-4 py-3">{{ $vehicle->make }} {{ $vehicle->model }}</td>
                    <td class="px-4 py-3">{{ $vehicle->manufacture_year }}</td>
                    <td class="px-4 py-3 metric-number">{{ number_format($vehicle->current_mileage_km) }} km</td>
                    @if(!auth()->user()->isCustomer())<td class="px-4 py-3">{{ $vehicle->owner->name }}</td>@endif
                    <td class="px-4 py-3">
                        @if($vehicle->isServiceDue())
                            <x-badge variant="danger">Service due</x-badge>
                        @else
                            <x-badge variant="success">Up to date</x-badge>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('vehicles.show', $vehicle) }}" class="inline-flex items-center gap-2 rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">View</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-4 py-10 text-center text-slate-500 dark:text-slate-400">No vehicles found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if(method_exists($vehicles, 'links'))
<div class="mt-4">{{ $vehicles->links() }}</div>
@endif
@endsection
