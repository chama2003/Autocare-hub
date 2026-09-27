@extends('layouts.app')
@section('title', 'Finance Dashboard')
@section('page-title', 'Revenue & Expense Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm p-5">
        <p class="text-sm text-slate-500">Today's Revenue</p>
        <p class="text-3xl font-bold text-emerald-700">Rs. {{ number_format($todayRevenue, 2) }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5">
        <p class="text-sm text-slate-500">This Month's Revenue</p>
        <p class="text-3xl font-bold text-emerald-700">Rs. {{ number_format($monthRevenue, 2) }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5">
        <p class="text-sm text-slate-500">This Month's Expenses</p>
        <p class="text-3xl font-bold text-red-600">Rs. {{ number_format($monthExpenses, 2) }}</p>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm p-5 mb-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="font-semibold">Revenue vs Expenses ({{ $year }})</h2>
        <form method="GET" class="text-sm">
            <select name="year" onchange="this.form.submit()" class="border rounded-lg px-2 py-1">
                @foreach(range(now()->year, now()->year - 3) as $y)
                <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
        </form>
    </div>
    <canvas id="revenueChart" height="90"></canvas>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl shadow-sm p-5">
        <h2 class="font-semibold mb-4">Expenses by Category</h2>
        <canvas id="expenseChart" height="200"></canvas>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-5">
        <h2 class="font-semibold mb-4">Record New Expense</h2>
        <form method="POST" action="{{ route('finance.expenses.store') }}" class="space-y-3">
            @csrf
            <select name="category" required class="w-full border rounded-lg px-3 py-2 text-sm">
                <option value="spare_parts_purchase">Spare Parts Purchase</option>
                <option value="utilities">Utilities</option>
                <option value="salary">Salary</option>
                <option value="rent">Rent</option>
                <option value="other">Other</option>
            </select>
            <input name="title" placeholder="Title" required class="w-full border rounded-lg px-3 py-2 text-sm">
            <div class="grid grid-cols-2 gap-3">
                <input type="number" step="0.01" name="amount" placeholder="Amount (Rs.)" required class="w-full border rounded-lg px-3 py-2 text-sm">
                <input type="date" name="expense_date" value="{{ now()->toDateString() }}" required class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>
            <textarea name="notes" rows="2" placeholder="Notes (optional)" class="w-full border rounded-lg px-3 py-2 text-sm"></textarea>
            <button class="bg-brand-600 hover:bg-brand-700 text-white px-5 py-2 rounded-lg text-sm font-medium">Save Expense</button>
        </form>
        <a href="{{ route('finance.expenses') }}" class="block text-xs text-brand-600 underline mt-3">View all expenses &rarr;</a>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
    const months = @json($months->pluck('label'));
    const revenue = @json($months->pluck('revenue'));
    const expenses = @json($months->pluck('expenses'));
    const expenseLabels = @json(array_keys($expensesByCategory->toArray()));
    const expenseValues = @json(array_values($expensesByCategory->toArray()));

    new Chart(document.getElementById('revenueChart'), {
        type: 'bar',
        data: {
            labels: months,
            datasets: [
                { label: 'Revenue', data: revenue, backgroundColor: '#2563eb' },
                { label: 'Expenses', data: expenses, backgroundColor: '#f87171' },
            ],
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } },
    });

    new Chart(document.getElementById('expenseChart'), {
        type: 'doughnut',
        data: {
            labels: expenseLabels,
            datasets: [{ data: expenseValues, backgroundColor: ['#2563eb', '#f59e0b', '#10b981', '#ef4444', '#8b5cf6'] }],
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } },
    });
</script>
@endpush
@endsection
