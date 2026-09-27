@extends('layouts.app')
@section('title', $invoice->invoice_number)
@section('page-title', 'Invoice ' . $invoice->invoice_number)

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6 max-w-3xl">
    <div class="flex justify-between items-start mb-6">
        <div>
            <h2 class="text-xl font-bold">AutoCare Hub</h2>
            <p class="text-sm text-slate-500">Invoice #{{ $invoice->invoice_number }}</p>
        </div>
        <div class="text-right">
            <p class="text-sm text-slate-500">Date</p>
            <p class="font-medium">{{ $invoice->created_at->format('d M Y') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-6 mb-6 text-sm">
        <div>
            <p class="text-slate-500">Billed To</p>
            <p class="font-medium">{{ $invoice->customer->name }}</p>
            <p>{{ $invoice->customer->phone }}</p>
        </div>
        <div>
            <p class="text-slate-500">Vehicle</p>
            <p class="font-medium">{{ $invoice->jobCard->vehicle->plate_number }} — {{ $invoice->jobCard->vehicle->make }} {{ $invoice->jobCard->vehicle->model }}</p>
            <p>Job Card: {{ $invoice->jobCard->job_card_number }}</p>
        </div>
    </div>

    <table class="w-full text-sm mb-6">
        <thead class="text-left text-slate-500 border-b">
            <tr><th class="py-2">Item</th><th class="text-right">Amount (Rs.)</th></tr>
        </thead>
        <tbody>
            @foreach($invoice->jobCard->serviceItems as $item)
            <tr class="border-b"><td class="py-2">Service: {{ $item->serviceType->name }}</td><td class="text-right">{{ number_format($item->cost, 2) }}</td></tr>
            @endforeach
            @foreach($invoice->jobCard->partsUsed as $part)
            <tr class="border-b"><td class="py-2">Part: {{ $part->sparePart->name }} x {{ $part->quantity }}</td><td class="text-right">{{ number_format($part->lineTotal(), 2) }}</td></tr>
            @endforeach
        </tbody>
    </table>

    <div class="flex justify-end">
        <div class="w-64 text-sm space-y-1">
            <div class="flex justify-between"><span>Labor Total</span><span>{{ number_format($invoice->labor_total, 2) }}</span></div>
            <div class="flex justify-between"><span>Parts Total</span><span>{{ number_format($invoice->parts_total, 2) }}</span></div>
            <div class="flex justify-between"><span>Tax</span><span>{{ number_format($invoice->tax_amount, 2) }}</span></div>
            <div class="flex justify-between"><span>Discount</span><span>-{{ number_format($invoice->discount_amount, 2) }}</span></div>
            <div class="flex justify-between font-bold text-base border-t pt-2"><span>Grand Total</span><span>Rs. {{ number_format($invoice->grand_total, 2) }}</span></div>
        </div>
    </div>

    <div class="mt-6 flex gap-3">
        <a href="{{ route('invoices.download', $invoice) }}" class="bg-brand-600 hover:bg-brand-700 text-white px-5 py-2 rounded-lg text-sm font-medium">
            <i class="fa-solid fa-download mr-1"></i> Download PDF
        </a>
        @if((auth()->user()->isStaff() || auth()->user()->isAdmin()) && $invoice->status !== 'paid')
        <form method="POST" action="{{ route('invoices.markPaid', $invoice) }}">
            @csrf
            <button class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2 rounded-lg text-sm font-medium">Mark as Paid</button>
        </form>
        @endif
    </div>
</div>
@endsection
