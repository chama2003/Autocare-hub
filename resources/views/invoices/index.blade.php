@extends('layouts.app')
@section('title', 'Invoices')
@section('page-title', 'Invoices')

@section('content')
<div class="overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-soft dark:border-slate-800 dark:bg-slate-900">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-left text-slate-500 dark:bg-slate-950/80 dark:text-slate-400">
                <tr><th class="px-4 py-3">Invoice #</th><th class="px-4 py-3">Vehicle</th><th class="px-4 py-3">Customer</th><th class="px-4 py-3">Total</th><th class="px-4 py-3">Status</th><th class="px-4 py-3 text-right"></th></tr>
            </thead>
            <tbody>
                @forelse($invoices as $invoice)
                <tr class="border-t border-slate-200 text-slate-700 dark:border-slate-800 dark:text-slate-200">
                    <td class="px-4 py-3 font-semibold">{{ $invoice->invoice_number }}</td>
                    <td class="px-4 py-3">{{ $invoice->jobCard->vehicle->plate_number }}</td>
                    <td class="px-4 py-3">{{ $invoice->customer->name }}</td>
                    <td class="px-4 py-3 metric-number">Rs. {{ number_format($invoice->grand_total, 2) }}</td>
                    <td class="px-4 py-3"><x-badge :variant="$invoice->status === 'paid' ? 'success' : 'warning'">{{ $invoice->status }}</x-badge></td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('invoices.show', $invoice) }}" class="inline-flex items-center rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-100 dark:hover:bg-slate-700">View</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-10 text-center text-slate-500 dark:text-slate-400">No invoices yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@if(method_exists($invoices, 'links'))<div class="mt-4">{{ $invoices->links() }}</div>@endif
@endsection
