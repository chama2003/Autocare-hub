@extends('layouts.app')
@section('title', 'Add Vehicle')
@section('page-title', 'Register Vehicle')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6 max-w-2xl">
    <form method="POST" action="{{ route('vehicles.store') }}" class="space-y-4">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Plate Number</label>
                <input name="plate_number" value="{{ old('plate_number') }}" required class="w-full border rounded-lg px-3 py-2 text-sm" placeholder="e.g. CAR-1234">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Type</label>
                <select name="type" class="w-full border rounded-lg px-3 py-2 text-sm">
                    <option value="car">Car</option>
                    <option value="bike">Bike</option>
                    <option value="van">Van</option>
                    <option value="truck">Truck</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Make</label>
                <input name="make" value="{{ old('make') }}" required class="w-full border rounded-lg px-3 py-2 text-sm" placeholder="Toyota">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Model</label>
                <input name="model" value="{{ old('model') }}" required class="w-full border rounded-lg px-3 py-2 text-sm" placeholder="Corolla">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Manufacture Year</label>
                <input type="number" name="manufacture_year" value="{{ old('manufacture_year') }}" required class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Color</label>
                <input name="color" value="{{ old('color') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Current Mileage (km)</label>
                <input type="number" name="current_mileage_km" value="{{ old('current_mileage_km', 0) }}" required class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Service Interval (km)</label>
                <input type="number" name="service_interval_km" value="{{ old('service_interval_km', 5000) }}" class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>
        </div>

        <div class="pt-2 flex gap-3">
            <button class="bg-brand-600 hover:bg-brand-700 text-white px-5 py-2 rounded-lg text-sm font-medium">Save Vehicle</button>
            <a href="{{ route('vehicles.index') }}" class="px-5 py-2 rounded-lg text-sm text-slate-600">Cancel</a>
        </div>
    </form>
</div>
@endsection
