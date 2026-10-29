@extends('layouts.app')
@section('title', 'Expenses')
@section('page-title', 'All Expenses')

@section('content')
<div class="overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-soft dark:border-slate-800 dark:bg-slate-900">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-left text-slate-500 dark:bg-slate-950/80 dark:text-slate-400">
                <tr><th class="px-4 py-3">Date</th><th class="px-4 py-3">Category</th><th class="px-4 py-3">Title</th><th class="px-4 py-3">Amount</th><th class="px-4 py-3">Recorded By</th></tr>
            </thead>
            <tbody>
                @forelse($expenses as $expense)
                <tr class="border-t border-slate-200 dark:border-slate-800">
                    <td class="px-4 py-3">{{ $expense->expense_date->format('d M Y') }}</td>
                    <td class="px-4 py-3 capitalize"><x-badge variant="warning">{{ str_replace('_',' ',$expense->category) }}</x-badge></td>
                    <td class="px-4 py-3 font-medium">{{ $expense->title }}</td>
                    <td class="px-4 py-3 metric-number">Rs. {{ number_format($expense->amount, 2) }}</td>
                    <td class="px-4 py-3">{{ $expense->recorder->name }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-10 text-center text-slate-500 dark:text-slate-400">No expenses recorded.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@if(method_exists($expenses, 'links'))<div class="mt-4">{{ $expenses->links() }}</div>@endif
@endsection
