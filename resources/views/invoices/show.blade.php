@extends('layouts.app')
@section('title', $invoice->invoice_number)
@section('page-title', 'Invoice ' . $invoice->invoice_number)

@section('content')
<div class="mx-auto max-w-4xl rounded-[30px] border border-slate-200 bg-white p-6 shadow-soft dark:border-slate-800 dark:bg-slate-900">
    <div class="flex flex-col gap-4 border-b border-slate-200 pb-6 md:flex-row md:items-start md:justify-between dark:border-slate-800">
        <div>
            <div class="flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-500 text-white"><i class="fa-solid fa-screwdriver-wrench"></i></div>
                <div>
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white">AutoCare Hub</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Invoice #{{ $invoice->invoice_number }}</p>
                </div>
            </div>
        </div>
        <div class="text-left md:text-right">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Issued</p>
            <p class="mt-2 text-base font-semibold text-slate-900 dark:text-white">{{ $invoice->created_at->format('d M Y') }}</p>
        </div>
    </div>

    <div class="mt-6 grid gap-6 md:grid-cols-2">
        <div class="rounded-2xl bg-slate-50 p-4 dark:bg-slate-950/50">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Billed to</p>
            <p class="mt-2 text-base font-semibold text-slate-900 dark:text-white">{{ $invoice->customer->name }}</p>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $invoice->customer->phone }}</p>
        </div>
        <div class="rounded-2xl bg-slate-50 p-4 dark:bg-slate-950/50">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Vehicle</p>
            <p class="mt-2 text-base font-semibold text-slate-900 dark:text-white">{{ $invoice->jobCard->vehicle->plate_number }} — {{ $invoice->jobCard->vehicle->make }} {{ $invoice->jobCard->vehicle->model }}</p>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Job Card: {{ $invoice->jobCard->job_card_number }}</p>
        </div>
    </div>

    <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-800">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-left text-slate-500 dark:bg-slate-950/80 dark:text-slate-400">
                <tr><th class="px-4 py-3">Item</th><th class="px-4 py-3 text-right">Amount (Rs.)</th></tr>
            </thead>
            <tbody>
                @foreach($invoice->jobCard->serviceItems as $item)
                <tr class="border-t border-slate-200 dark:border-slate-800"><td class="px-4 py-3">Service: {{ $item->serviceType->name }}</td><td class="px-4 py-3 text-right metric-number">{{ number_format($item->cost, 2) }}</td></tr>
                @endforeach
                @foreach($invoice->jobCard->partsUsed as $part)
                <tr class="border-t border-slate-200 dark:border-slate-800"><td class="px-4 py-3">Part: {{ $part->sparePart->name }} x {{ $part->quantity }}</td><td class="px-4 py-3 text-right metric-number">{{ number_format($part->lineTotal(), 2) }}</td></tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6 flex justify-end">
        <div class="w-full max-w-xs rounded-2xl bg-slate-50 p-4 dark:bg-slate-950/50">
            <div class="space-y-2 text-sm text-slate-600 dark:text-slate-300">
                <div class="flex items-center justify-between"><span>Labor Total</span><span class="metric-number">{{ number_format($invoice->labor_total, 2) }}</span></div>
                <div class="flex items-center justify-between"><span>Parts Total</span><span class="metric-number">{{ number_format($invoice->parts_total, 2) }}</span></div>
                <div class="flex items-center justify-between"><span>Tax</span><span class="metric-number">{{ number_format($invoice->tax_amount, 2) }}</span></div>
                <div class="flex items-center justify-between"><span>Discount</span><span class="metric-number">-{{ number_format($invoice->discount_amount, 2) }}</span></div>
            </div>
            <div class="mt-3 border-t border-slate-200 pt-3 dark:border-slate-800">
                <div class="flex items-center justify-between text-base font-bold text-slate-900 dark:text-white"><span>Grand Total</span><span class="metric-number">Rs. {{ number_format($invoice->grand_total, 2) }}</span></div>
            </div>
        </div>
    </div>

    <div class="mt-6 flex flex-col gap-3 sm:flex-row">
        <a href="{{ route('invoices.download', $invoice) }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 to-violet-500 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-violet-500/20 hover:from-indigo-400 hover:to-violet-400">
            <i class="fa-solid fa-download"></i> Download PDF
        </a>
        @if((auth()->user()->isStaff() || auth()->user()->isAdmin()) && $invoice->status !== 'paid')
        <form method="POST" action="{{ route('invoices.markPaid', $invoice) }}">
            @csrf
            <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-500 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-emerald-500/20 hover:bg-emerald-400">
                <i class="fa-solid fa-circle-check"></i> Mark as Paid
            </button>
        </form>
        @endif
    </div>
</div>
@endsection
