@extends('layouts.app')
@section('title', $vehicle->plate_number)
@section('page-title', 'Vehicle details')

@section('content')
<div class="grid gap-6 lg:grid-cols-[0.9fr_1.1fr]">
    <x-card class="p-6">
        <div class="flex items-center gap-4">
            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500/15 to-violet-500/15 text-xl text-violet-600 dark:text-violet-300">
                <i class="fa-solid {{ $vehicle->type === 'bike' ? 'fa-motorcycle' : 'fa-car' }}"></i>
            </div>
            <div>
                <p class="text-xl font-bold text-slate-900 dark:text-white">{{ $vehicle->plate_number }}</p>
                <p class="text-sm text-slate-500 dark:text-slate-400">{{ $vehicle->make }} {{ $vehicle->model }} ({{ $vehicle->manufacture_year }})</p>
            </div>
        </div>

        <dl class="mt-6 space-y-3 text-sm">
            <div class="flex items-center justify-between rounded-xl bg-slate-50 px-3 py-2 dark:bg-slate-950/50"><dt class="text-slate-500 dark:text-slate-400">Owner</dt><dd class="font-medium text-slate-900 dark:text-white">{{ $vehicle->owner->name }}</dd></div>
            <div class="flex items-center justify-between rounded-xl bg-slate-50 px-3 py-2 dark:bg-slate-950/50"><dt class="text-slate-500 dark:text-slate-400">Color</dt><dd class="font-medium text-slate-900 dark:text-white">{{ $vehicle->color ?? '—' }}</dd></div>
            <div class="flex items-center justify-between rounded-xl bg-slate-50 px-3 py-2 dark:bg-slate-950/50"><dt class="text-slate-500 dark:text-slate-400">Mileage</dt><dd class="font-medium tabular-nums text-slate-900 dark:text-white">{{ number_format($vehicle->current_mileage_km) }} km</dd></div>
            <div class="flex items-center justify-between rounded-xl bg-slate-50 px-3 py-2 dark:bg-slate-950/50"><dt class="text-slate-500 dark:text-slate-400">Service interval</dt><dd class="font-medium text-slate-900 dark:text-white">{{ $vehicle->service_interval_km }} km / {{ $vehicle->service_interval_months }} mo</dd></div>
            <div class="flex items-center justify-between rounded-xl bg-slate-50 px-3 py-2 dark:bg-slate-950/50"><dt class="text-slate-500 dark:text-slate-400">Last service</dt><dd class="font-medium text-slate-900 dark:text-white">{{ optional($vehicle->last_service_date)->format('d M Y') ?? 'Never' }}</dd></div>
        </dl>

        @if($vehicle->isServiceDue())
            <div class="mt-5 rounded-2xl border border-rose-200 bg-rose-50 px-3 py-2 text-xs font-medium text-rose-700 dark:border-rose-500/20 dark:bg-rose-500/10 dark:text-rose-300">This vehicle is due for its next service.</div>
        @endif

        <a href="{{ route('appointments.create') }}" class="mt-6 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 to-violet-500 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-violet-500/20 hover:from-indigo-400 hover:to-violet-400">
            <i class="fa-solid fa-calendar-plus"></i> Book Service
        </a>
    </x-card>

    <x-card class="p-6">
        <div class="mb-5 flex items-center justify-between">
            <h3 class="text-lg font-semibold">Complete service history</h3>
            <x-badge variant="primary">{{ $vehicle->serviceHistory->count() }} records</x-badge>
        </div>
        <div class="space-y-4">
            @forelse($vehicle->serviceHistory as $jc)
            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-950/50">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="font-semibold text-slate-900 dark:text-white">{{ $jc->job_card_number }}</p>
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $jc->opened_at->format('d M Y') }} • {{ number_format($jc->mileage_at_service_km) }} km</p>
                    </div>
                    <x-badge :variant="match($jc->status) { 'pending' => 'warning', 'in_progress' => 'info', 'quality_check' => 'primary', 'completed' => 'success', default => 'default' }">{{ str_replace('_',' ',$jc->status) }}</x-badge>
                </div>
                @if($jc->serviceItems->count())
                <p class="mt-3 text-xs text-slate-500 dark:text-slate-400">Services: {{ $jc->serviceItems->pluck('serviceType.name')->join(', ') }}</p>
                @endif
                @if($jc->partsUsed->count())
                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Parts: {{ $jc->partsUsed->map(fn($p) => $p->sparePart->name.' x'.$p->quantity)->join(', ') }}</p>
                @endif
                @if($jc->invoice)
                <div class="mt-3"><a href="{{ route('invoices.show', $jc->invoice) }}" class="text-xs font-medium text-violet-600 dark:text-violet-300">View invoice ({{ $jc->invoice->invoice_number }})</a></div>
                @endif
            </div>
            @empty
            <x-empty-state title="No service history" message="This vehicle has no completed workshop visits yet." />
            @endforelse
        </div>
    </x-card>
</div>
@endsection
