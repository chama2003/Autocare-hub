@extends('layouts.app')
@section('title', 'Inventory')
@section('page-title', 'Spare Parts Inventory')

@section('content')
<div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
    <form method="GET" class="flex flex-col gap-2 sm:flex-row">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search parts..." class="input-base w-full sm:w-64">
        <label class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200">
            <input type="checkbox" name="low_stock" value="1" {{ request('low_stock') ? 'checked' : '' }} onchange="this.form.submit()" class="h-4 w-4 rounded border-slate-300 text-violet-600 focus:ring-violet-500">
            Low stock only
        </label>
    </form>
    <a href="{{ route('inventory.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 to-violet-500 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-violet-500/20 hover:from-indigo-400 hover:to-violet-400">
        <i class="fa-solid fa-plus"></i> Add Part
    </a>
</div>

<div class="overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-soft dark:border-slate-800 dark:bg-slate-900">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-left text-slate-500 dark:bg-slate-950/80 dark:text-slate-400">
                <tr><th class="px-4 py-3">SKU</th><th class="px-4 py-3">Name</th><th class="px-4 py-3">Category</th><th class="px-4 py-3">Stock</th><th class="px-4 py-3">Unit Price</th><th class="px-4 py-3 text-right"></th></tr>
            </thead>
            <tbody>
                @forelse($spareParts as $part)
                <tr class="border-t border-slate-200 {{ $part->isLowStock() ? 'bg-rose-50/60 dark:bg-rose-500/5' : '' }} dark:border-slate-800">
                    <td class="px-4 py-3 font-medium">{{ $part->sku }}</td>
                    <td class="px-4 py-3 font-semibold">{{ $part->name }}</td>
                    <td class="px-4 py-3 capitalize">{{ $part->category }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <div class="min-w-0 flex-1">
                                <div class="mb-1 flex items-center justify-between text-[11px] font-medium text-slate-500 dark:text-slate-400"><span>{{ $part->quantity_in_stock }} {{ $part->unit }}</span>@if($part->isLowStock())<span class="text-rose-600 dark:text-rose-300">Low</span>@endif</div>
                                <div class="h-2 w-full rounded-full bg-slate-200 dark:bg-slate-700"><div class="h-2 rounded-full {{ $part->isLowStock() ? 'bg-rose-500' : 'bg-emerald-500' }}" style="width: {{ min(100, ($part->quantity_in_stock / max(1, $part->reorder_level ?? 10)) * 100) }}%"></div></div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3">Rs. {{ number_format($part->unit_price, 2) }}</td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <form method="POST" action="{{ route('inventory.restock', $part) }}" class="inline-flex items-center gap-2">
                                @csrf
                                <input type="number" name="quantity" min="1" placeholder="Qty" class="w-16 rounded-lg border border-slate-200 bg-white px-2 py-1 text-xs dark:border-slate-700 dark:bg-slate-950">
                                <button class="rounded-lg bg-emerald-500 px-2.5 py-1.5 text-[11px] font-semibold text-white hover:bg-emerald-400">Restock</button>
                            </form>
                            <a href="{{ route('inventory.edit', $part) }}" class="rounded-lg bg-slate-100 px-2.5 py-1.5 text-[11px] font-semibold text-slate-700 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-100 dark:hover:bg-slate-700">Edit</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-10 text-center text-slate-500 dark:text-slate-400">No spare parts found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@if(method_exists($spareParts, 'links'))<div class="mt-4">{{ $spareParts->links() }}</div>@endif
@endsection
