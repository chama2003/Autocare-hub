@extends('layouts.app')
@section('title', 'Expenses')
@section('page-title', 'All Expenses')

@section('content')
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 text-left text-slate-500">
            <tr><th class="px-4 py-3">Date</th><th class="px-4 py-3">Category</th><th class="px-4 py-3">Title</th><th class="px-4 py-3">Amount</th><th class="px-4 py-3">Recorded By</th></tr>
        </thead>
        <tbody>
            @forelse($expenses as $expense)
            <tr class="border-t">
                <td class="px-4 py-3">{{ $expense->expense_date->format('d M Y') }}</td>
                <td class="px-4 py-3 capitalize">{{ str_replace('_',' ',$expense->category) }}</td>
                <td class="px-4 py-3">{{ $expense->title }}</td>
                <td class="px-4 py-3">Rs. {{ number_format($expense->amount, 2) }}</td>
                <td class="px-4 py-3">{{ $expense->recorder->name }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-4 py-8 text-center text-slate-400">No expenses recorded.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@if(method_exists($expenses, 'links'))<div class="mt-4">{{ $expenses->links() }}</div>@endif
@endsection
