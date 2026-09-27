@extends('layouts.app')
@section('title', 'Book Appointment')
@section('page-title', 'Book a Service Appointment')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6 max-w-xl">
    <form method="POST" action="{{ route('appointments.store') }}" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium mb-1">Vehicle</label>
            <select name="vehicle_id" required class="w-full border rounded-lg px-3 py-2 text-sm">
                <option value="">Select vehicle</option>
                @foreach($vehicles as $v)
                <option value="{{ $v->id }}">{{ $v->plate_number }} — {{ $v->make }} {{ $v->model }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Service Type</label>
            <select name="service_type_id" required class="w-full border rounded-lg px-3 py-2 text-sm">
                <option value="">Select service</option>
                @foreach($serviceTypes as $s)
                <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->estimated_minutes }} min)</option>
                @endforeach
            </select>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Date</label>
                <input type="date" name="appointment_date" min="{{ now()->toDateString() }}" required class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Time Slot</label>
                <input type="time" name="appointment_time" required class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Notes (optional)</label>
            <textarea name="notes" rows="3" class="w-full border rounded-lg px-3 py-2 text-sm" placeholder="Describe the issue..."></textarea>
        </div>
        <div class="pt-2 flex gap-3">
            <button class="bg-brand-600 hover:bg-brand-700 text-white px-5 py-2 rounded-lg text-sm font-medium">Request Appointment</button>
            <a href="{{ route('appointments.index') }}" class="px-5 py-2 rounded-lg text-sm text-slate-600">Cancel</a>
        </div>
    </form>
</div>
@endsection
