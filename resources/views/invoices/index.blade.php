@extends('layouts.app')
@section('title', 'Invoices')
@section('page-title', 'Invoices')

@section('content')
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 text-left text-slate-500">
            <tr><th class="px-4 py-3">Invoice #</th><th class="px-4 py-3">Vehicle</th><th class="px-4 py-3">Customer</th><th class="px-4 py-3">Total</th><th class="px-4 py-3">Status</th><th></th></tr>
        </thead>
        <tbody>
            @forelse($invoices as $invoice)
            <tr class="border-t">
                <td class="px-4 py-3 font-medium">{{ $invoice->invoice_number }}</td>
                <td class="px-4 py-3">{{ $invoice->jobCard->vehicle->plate_number }}</td>
                <td class="px-4 py-3">{{ $invoice->customer->name }}</td>
                <td class="px-4 py-3">Rs. {{ number_format($invoice->grand_total, 2) }}</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-0.5 rounded-full text-xs {{ $invoice->status === 'paid' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }} capitalize">{{ $invoice->status }}</span>
                </td>
                <td class="px-4 py-3 text-right">
                    <a href="{{ route('invoices.show', $invoice) }}" class="text-brand-600 hover:underline">View</a>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-4 py-8 text-center text-slate-400">No invoices yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@if(method_exists($invoices, 'links'))<div class="mt-4">{{ $invoices->links() }}</div>@endif
@endsection
