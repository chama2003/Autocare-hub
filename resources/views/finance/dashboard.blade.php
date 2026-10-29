@extends('layouts.app')
@section('title', 'Finance Dashboard')
@section('page-title', 'Revenue & Expense Dashboard')

@section('content')
<div class="grid gap-4 md:grid-cols-3">
    <x-stat-card icon="fa-solid fa-dollar-sign" label="Today's Revenue" value="Rs. {{ number_format($todayRevenue, 2) }}" tone="success" />
    <x-stat-card icon="fa-solid fa-chart-line" label="This Month's Revenue" value="Rs. {{ number_format($monthRevenue, 2) }}" tone="primary" />
    <x-stat-card icon="fa-solid fa-wallet" label="This Month's Expenses" value="Rs. {{ number_format($monthExpenses, 2) }}" tone="danger" />
</div>

<div class="mt-6 rounded-[28px] border border-slate-200 bg-white p-5 shadow-soft dark:border-slate-800 dark:bg-slate-900">
    <div class="mb-4 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Revenue vs Expenses ({{ $year }})</h2>
        <form method="GET" class="text-sm">
            <select name="year" onchange="this.form.submit()" class="input-base w-full sm:w-32">
                @foreach(range(now()->year, now()->year - 3) as $y)
                <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
        </form>
    </div>
    <canvas id="revenueChart" height="90"></canvas>
</div>

<div class="mt-6 grid gap-6 lg:grid-cols-2">
    <x-card class="p-5">
        <h2 class="mb-4 text-lg font-semibold">Expenses by category</h2>
        <canvas id="expenseChart" height="200"></canvas>
    </x-card>

    <x-card class="p-5">
        <h2 class="mb-4 text-lg font-semibold">Record new expense</h2>
        <form method="POST" action="{{ route('finance.expenses.store') }}" class="space-y-3">
            @csrf
            <select name="category" required class="input-base">
                <option value="spare_parts_purchase">Spare Parts Purchase</option>
                <option value="utilities">Utilities</option>
                <option value="salary">Salary</option>
                <option value="rent">Rent</option>
                <option value="other">Other</option>
            </select>
            <input name="title" placeholder="Title" required class="input-base">
            <div class="grid gap-3 sm:grid-cols-2">
                <input type="number" step="0.01" name="amount" placeholder="Amount (Rs.)" required class="input-base">
                <input type="date" name="expense_date" value="{{ now()->toDateString() }}" required class="input-base">
            </div>
            <textarea name="notes" rows="2" placeholder="Notes (optional)" class="input-base"></textarea>
            <button class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 to-violet-500 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-violet-500/20 hover:from-indigo-400 hover:to-violet-400">Save Expense</button>
        </form>
        <a href="{{ route('finance.expenses') }}" class="mt-4 inline-block text-sm font-medium text-violet-600 dark:text-violet-300">View all expenses →</a>
    </x-card>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
    const months = @json($months->pluck('label'));
    const revenue = @json($months->pluck('revenue'));
    const expenses = @json($months->pluck('expenses'));
    const expenseLabels = @json(array_keys($expensesByCategory->toArray()));
    const expenseValues = @json(array_values($expensesByCategory->toArray()));

    const isDark = document.documentElement.classList.contains('dark');

    new Chart(document.getElementById('revenueChart'), {
        type: 'bar',
        data: {
            labels: months,
            datasets: [
                { label: 'Revenue', data: revenue, backgroundColor: '#6366F1', borderRadius: 8 },
                { label: 'Expenses', data: expenses, backgroundColor: '#F87171', borderRadius: 8 },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom', labels: { color: isDark ? '#e2e8f0' : '#334155' } } },
            scales: { x: { ticks: { color: isDark ? '#cbd5e1' : '#475569' }, grid: { display: false } }, y: { ticks: { color: isDark ? '#cbd5e1' : '#475569' }, grid: { color: isDark ? 'rgba(148,163,184,0.15)' : 'rgba(15,23,42,0.06)' } } }
        }
    });

    new Chart(document.getElementById('expenseChart'), {
        type: 'doughnut',
        data: {
            labels: expenseLabels,
            datasets: [{ data: expenseValues, backgroundColor: ['#6366F1', '#F59E0B', '#10B981', '#F87171', '#8B5CF6'] }],
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom', labels: { color: isDark ? '#e2e8f0' : '#334155' } } } }
    });
</script>
@endpush
@endsection
