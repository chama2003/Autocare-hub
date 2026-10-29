@extends('layouts.app')
@section('title', $jobCard->job_card_number)
@section('page-title', 'Job Card: ' . $jobCard->job_card_number)

@section('content')
@php($vehicle = $jobCard->vehicle)
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Left: summary + status --}}
    <div class="bg-white rounded-xl shadow-sm p-6 lg:col-span-1 space-y-4">
        <div>
            <p class="text-sm text-slate-500">Vehicle</p>
            @if($vehicle)
                <p class="font-semibold">{{ $vehicle->plate_number }} — {{ $vehicle->make }} {{ $vehicle->model }}</p>
                <p class="text-xs text-slate-400">Owner: {{ $vehicle->owner?->name ?? 'Unknown owner' }}</p>
            @else
                <p class="font-semibold text-slate-600">Vehicle not assigned</p>
                <p class="text-xs text-slate-400">No vehicle is linked to this job card.</p>
            @endif
        </div>
        <div>
            <p class="text-sm text-slate-500">Mileage at Service</p>
            <p class="font-medium">{{ number_format($jobCard->mileage_at_service_km) }} km</p>
        </div>
        <div>
            <p class="text-sm text-slate-500">Complaint</p>
            <p class="text-sm">{{ $jobCard->customer_complaint ?? '—' }}</p>
        </div>

        <form method="POST" action="{{ route('jobcards.status', $jobCard) }}" class="space-y-3 pt-3 border-t">
            @csrf @method('PUT')
            <label class="block text-sm font-medium">Status</label>
            <select name="status" class="w-full border rounded-lg px-3 py-2 text-sm">
                @foreach(['pending','in_progress','quality_check','completed'] as $s)
                <option value="{{ $s }}" {{ $jobCard->status === $s ? 'selected' : '' }}>{{ ucwords(str_replace('_',' ',$s)) }}</option>
                @endforeach
            </select>
            <label class="block text-sm font-medium">Labor Hours</label>
            <input type="number" step="0.25" name="labor_hours" value="{{ $jobCard->labor_hours }}" class="w-full border rounded-lg px-3 py-2 text-sm">
            <label class="block text-sm font-medium">Diagnosis</label>
            <textarea name="diagnosis" rows="3" class="w-full border rounded-lg px-3 py-2 text-sm">{{ $jobCard->diagnosis }}</textarea>
            <button class="w-full rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-300 focus:ring-offset-2">Update Status</button>
        </form>

        <div class="pt-3 border-t text-sm">
            <div class="flex justify-between"><span class="text-slate-500">Labor Total</span><span>Rs. {{ number_format($jobCard->laborTotal(), 2) }}</span></div>
            <div class="flex justify-between"><span class="text-slate-500">Parts Total</span><span>Rs. {{ number_format($jobCard->partsTotal(), 2) }}</span></div>
            <div class="flex justify-between font-semibold pt-1 border-t mt-1"><span>Grand Total</span><span>Rs. {{ number_format($jobCard->grandTotal(), 2) }}</span></div>
        </div>

        @if($jobCard->invoice)
            <a href="{{ route('invoices.show', $jobCard->invoice) }}" class="block text-center rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-500">View Invoice</a>
        @elseif($jobCard->status === 'completed')
            <div class="rounded-xl border border-emerald-100 bg-emerald-50 px-3 py-2 text-xs font-medium text-emerald-700">
                Ready for checkout
            </div>
            <form method="POST" action="{{ route('invoices.generate', $jobCard) }}" class="space-y-2 pt-2">
                @csrf
                <label class="block text-xs text-slate-500">Tax Rate (%)</label>
                <input type="number" step="0.01" name="tax_rate" value="0" class="w-full border rounded-lg px-3 py-2 text-sm focus:border-slate-400 focus:outline-none">
                <label class="block text-xs text-slate-500">Discount (Rs.)</label>
                <input type="number" step="0.01" name="discount_amount" value="0" class="w-full border rounded-lg px-3 py-2 text-sm focus:border-slate-400 focus:outline-none">
                <button class="w-full rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-300 focus:ring-offset-2">Check Out</button>
            </form>
        @endif
    </div>

    {{-- Right: services + parts --}}
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="font-semibold mb-3">Services Performed</h2>
            <div class="space-y-2 mb-4">
                @forelse($jobCard->serviceItems as $item)
                <div class="flex justify-between text-sm border-b pb-2">
                    <span>{{ $item->serviceType->name }} <span class="text-slate-400 text-xs">{{ $item->notes }}</span></span>
                    <span>Rs. {{ number_format($item->cost, 2) }}</span>
                </div>
                @empty
                <p class="text-sm text-slate-400">No services logged yet.</p>
                @endforelse
            </div>
            <form method="POST" action="{{ route('jobcards.services.add', $jobCard) }}" class="flex gap-2">
                @csrf
                <select name="service_type_id" required class="flex-1 border rounded-lg px-3 py-2 text-sm">
                    @foreach($serviceTypes as $s)
                    <option value="{{ $s->id }}" data-cost="{{ $s->base_labor_cost }}">{{ $s->name }}</option>
                    @endforeach
                </select>
                <input type="number" step="0.01" name="cost" placeholder="Cost" required class="w-32 border rounded-lg px-3 py-2 text-sm">
                <button class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">Add</button>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="font-semibold mb-3">Spare Parts Used</h2>
            <div class="space-y-2 mb-4">
                @forelse($jobCard->partsUsed as $part)
                <div class="flex justify-between text-sm border-b pb-2">
                    <span>{{ $part->sparePart->name }} x {{ $part->quantity }}</span>
                    <span>Rs. {{ number_format($part->lineTotal(), 2) }}</span>
                </div>
                @empty
                <p class="text-sm text-slate-400">No parts assigned yet.</p>
                @endforelse
            </div>
            <form method="POST" action="{{ route('jobcards.parts.add', $jobCard) }}" class="flex gap-2">
                @csrf
                <select name="spare_part_id" required class="flex-1 border rounded-lg px-3 py-2 text-sm">
                    @foreach($spareParts as $p)
                    <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->quantity_in_stock }} {{ $p->unit }} in stock)</option>
                    @endforeach
                </select>
                <input type="number" name="quantity" value="1" min="1" required class="w-24 border rounded-lg px-3 py-2 text-sm">
                <button class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">Assign</button>
            </form>
        </div>
    </div>
</div>
@endsection