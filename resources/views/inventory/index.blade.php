@extends('layouts.app')
@section('title', 'Inventory')
@section('page-title', 'Spare Parts Inventory')

@section('content')
<div class="flex justify-between items-center mb-5">
    <form method="GET" class="flex gap-2 text-sm">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search parts..." class="border rounded-lg px-3 py-2">
        <label class="flex items-center gap-1 px-3 py-2 border rounded-lg">
            <input type="checkbox" name="low_stock" value="1" {{ request('low_stock') ? 'checked' : '' }} onchange="this.form.submit()"> Low stock only
        </label>
    </form>
    <a href="{{ route('inventory.create') }}" class="bg-brand-600 hover:bg-brand-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
        <i class="fa-solid fa-plus mr-1"></i> Add Part
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 text-left text-slate-500">
            <tr><th class="px-4 py-3">SKU</th><th class="px-4 py-3">Name</th><th class="px-4 py-3">Category</th><th class="px-4 py-3">Stock</th><th class="px-4 py-3">Unit Price</th><th></th></tr>
        </thead>
        <tbody>
            @forelse($spareParts as $part)
            <tr class="border-t {{ $part->isLowStock() ? 'bg-red-50' : '' }}">
                <td class="px-4 py-3">{{ $part->sku }}</td>
                <td class="px-4 py-3 font-medium">{{ $part->name }}</td>
                <td class="px-4 py-3 capitalize">{{ $part->category }}</td>
                <td class="px-4 py-3">
                    {{ $part->quantity_in_stock }} {{ $part->unit }}
                    @if($part->isLowStock())<span class="ml-1 text-xs px-2 py-0.5 rounded-full bg-red-100 text-red-700">Low</span>@endif
                </td>
                <td class="px-4 py-3">Rs. {{ number_format($part->unit_price, 2) }}</td>
                <td class="px-4 py-3 text-right space-x-2">
                    <form method="POST" action="{{ route('inventory.restock', $part) }}" class="inline-flex gap-1">
                        @csrf
                        <input type="number" name="quantity" min="1" placeholder="Qty" class="w-16 border rounded px-1 text-xs">
                        <button class="text-emerald-600 hover:underline text-xs">Restock</button>
                    </form>
                    <a href="{{ route('inventory.edit', $part) }}" class="text-brand-600 hover:underline text-xs">Edit</a>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-4 py-8 text-center text-slate-400">No spare parts found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@if(method_exists($spareParts, 'links'))<div class="mt-4">{{ $spareParts->links() }}</div>@endif
@endsection
