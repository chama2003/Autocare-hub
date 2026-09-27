@extends('layouts.app')
@section('title', 'Edit Spare Part')
@section('page-title', 'Edit Spare Part')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6 max-w-xl">
    <form method="POST" action="{{ route('inventory.update', $sparePart) }}" class="space-y-4">
        @csrf @method('PUT')
        <div class="grid grid-cols-2 gap-4">
            <div><label class="block text-sm font-medium mb-1">Name</label><input name="name" value="{{ $sparePart->name }}" required class="w-full border rounded-lg px-3 py-2 text-sm"></div>
            <div><label class="block text-sm font-medium mb-1">Category</label><input name="category" value="{{ $sparePart->category }}" class="w-full border rounded-lg px-3 py-2 text-sm"></div>
            <div><label class="block text-sm font-medium mb-1">Unit</label><input name="unit" value="{{ $sparePart->unit }}" class="w-full border rounded-lg px-3 py-2 text-sm"></div>
            <div><label class="block text-sm font-medium mb-1">Quantity in Stock</label><input type="number" name="quantity_in_stock" value="{{ $sparePart->quantity_in_stock }}" required class="w-full border rounded-lg px-3 py-2 text-sm"></div>
            <div><label class="block text-sm font-medium mb-1">Low Stock Threshold</label><input type="number" name="low_stock_threshold" value="{{ $sparePart->low_stock_threshold }}" required class="w-full border rounded-lg px-3 py-2 text-sm"></div>
            <div><label class="block text-sm font-medium mb-1">Unit Cost (Rs.)</label><input type="number" step="0.01" name="unit_cost" value="{{ $sparePart->unit_cost }}" required class="w-full border rounded-lg px-3 py-2 text-sm"></div>
            <div><label class="block text-sm font-medium mb-1">Unit Price (Rs.)</label><input type="number" step="0.01" name="unit_price" value="{{ $sparePart->unit_price }}" required class="w-full border rounded-lg px-3 py-2 text-sm"></div>
        </div>
        <div class="pt-2 flex gap-3">
            <button class="bg-brand-600 hover:bg-brand-700 text-white px-5 py-2 rounded-lg text-sm font-medium">Update Part</button>
            <a href="{{ route('inventory.index') }}" class="px-5 py-2 rounded-lg text-sm text-slate-600">Cancel</a>
        </div>
    </form>
</div>
@endsection
