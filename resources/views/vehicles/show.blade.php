@extends('layouts.app')
@section('title', $vehicle->plate_number)
@section('page-title', 'Vehicle Details')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="bg-white rounded-xl shadow-sm p-6 lg:col-span-1">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-12 h-12 rounded-full bg-brand-100 flex items-center justify-center">
                <i class="fa-solid {{ $vehicle->type === 'bike' ? 'fa-motorcycle' : 'fa-car' }} text-brand-600 text-xl"></i>
            </div>
            <div>
                <p class="font-bold text-lg">{{ $vehicle->plate_number }}</p>
                <p class="text-sm text-slate-500">{{ $vehicle->make }} {{ $vehicle->model }} ({{ $vehicle->manufacture_year }})</p>
            </div>
        </div>
        <dl class="text-sm space-y-2">
            <div class="flex justify-between"><dt class="text-slate-500">Owner</dt><dd>{{ $vehicle->owner->name }}</dd></div>
            <div class="flex justify-between"><dt class="text-slate-500">Color</dt><dd>{{ $vehicle->color ?? '—' }}</dd></div>
            <div class="flex justify-between"><dt class="text-slate-500">Mileage</dt><dd>{{ number_format($vehicle->current_mileage_km) }} km</dd></div>
            <div class="flex justify-between"><dt class="text-slate-500">Service Interval</dt><dd>{{ $vehicle->service_interval_km }} km / {{ $vehicle->service_interval_months }} mo</dd></div>
            <div class="flex justify-between"><dt class="text-slate-500">Last Service</dt><dd>{{ optional($vehicle->last_service_date)->format('d M Y') ?? 'Never' }}</dd></div>
        </dl>
        @if($vehicle->isServiceDue())
        <div class="mt-4 bg-red-50 text-red-700 text-xs rounded-lg px-3 py-2">This vehicle is due for its next service.</div>
        @endif
        <a href="{{ route('appointments.create') }}" class="mt-4 block text-center bg-brand-600 hover:bg-brand-700 text-white px-4 py-2 rounded-lg text-sm font-medium">Book Service</a>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 lg:col-span-2">
        <h2 class="font-semibold mb-4">Complete Service History</h2>
        <div class="space-y-4">
            @forelse($vehicle->serviceHistory as $jc)
            <div class="border rounded-lg p-4">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="font-medium">{{ $jc->job_card_number }}</p>
                        <p class="text-xs text-slate-400">{{ $jc->opened_at->format('d M Y') }} &middot; {{ number_format($jc->mileage_at_service_km) }} km</p>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-xs bg-brand-100 text-brand-700 capitalize">{{ str_replace('_',' ',$jc->status) }}</span>
                </div>
                @if($jc->serviceItems->count())
                <p class="text-xs text-slate-500 mt-2">Services: {{ $jc->serviceItems->pluck('serviceType.name')->join(', ') }}</p>
                @endif
                @if($jc->partsUsed->count())
                <p class="text-xs text-slate-500">Parts: {{ $jc->partsUsed->map(fn($p) => $p->sparePart->name.' x'.$p->quantity)->join(', ') }}</p>
                @endif
                @if($jc->invoice)
                <a href="{{ route('invoices.show', $jc->invoice) }}" class="text-xs text-brand-600 underline">View Invoice ({{ $jc->invoice->invoice_number }})</a>
                @endif
            </div>
            @empty
            <p class="text-sm text-slate-400">No service history yet.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
