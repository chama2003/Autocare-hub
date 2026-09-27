@extends('layouts.app')
@section('title', 'Open Job Card')
@section('page-title', 'Open Job Card')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6 max-w-xl">
    <form method="POST" action="{{ route('jobcards.store') }}" class="space-y-4">
        @csrf
        @if($appointment)<input type="hidden" name="appointment_id" value="{{ $appointment->id }}">@endif
        <div>
            <label class="block text-sm font-medium mb-1">Vehicle</label>
            <select name="vehicle_id" required class="w-full border rounded-lg px-3 py-2 text-sm">
                @foreach($vehicles as $v)
                <option value="{{ $v->id }}" {{ $appointment && $appointment->vehicle_id === $v->id ? 'selected' : '' }}>{{ $v->plate_number }} — {{ $v->make }} {{ $v->model }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Assign Mechanic</label>
            <select name="mechanic_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                <option value="">Unassigned</option>
                @foreach($mechanics as $m)
                <option value="{{ $m->id }}">{{ $m->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Mileage at Service (km)</label>
                <input type="number" name="mileage_at_service_km" required class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Labor Rate / hr (Rs.)</label>
                <input type="number" step="0.01" name="labor_rate_per_hour" value="1500" required class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Customer Complaint</label>
            <textarea name="customer_complaint" rows="3" class="w-full border rounded-lg px-3 py-2 text-sm"></textarea>
        </div>
        <div class="pt-2 flex gap-3">
            <button class="bg-brand-600 hover:bg-brand-700 text-white px-5 py-2 rounded-lg text-sm font-medium">Open Job Card</button>
            <a href="{{ route('jobcards.index') }}" class="px-5 py-2 rounded-lg text-sm text-slate-600">Cancel</a>
        </div>
    </form>
</div>
@endsection
