<?php

namespace App\Http\Controllers;

use App\Models\SparePart;
use Illuminate\Http\Request;

class SparePartController extends Controller
{
    public function index(Request $request)
    {
        $query = SparePart::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->string('search') . '%')
                ->orWhere('sku', 'like', '%' . $request->string('search') . '%');
        }

        if ($request->boolean('low_stock')) {
            $query->whereColumn('quantity_in_stock', '<=', 'low_stock_threshold');
        }

        $spareParts = $query->orderBy('name')->paginate(20)->withQueryString();

        return view('inventory.index', compact('spareParts'));
    }

    public function create()
    {
        return view('inventory.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'sku' => 'required|string|max:30|unique:spare_parts,sku',
            'name' => 'required|string|max:100',
            'category' => 'nullable|string|max:50',
            'unit' => 'required|string|max:20',
            'quantity_in_stock' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:0',
            'unit_cost' => 'required|numeric|min:0',
            'unit_price' => 'required|numeric|min:0',
        ]);

        SparePart::create($data);

        return redirect()->route('inventory.index')->with('success', 'Spare part added to inventory.');
    }

    public function edit(SparePart $sparePart)
    {
        return view('inventory.edit', compact('sparePart'));
    }

    public function update(Request $request, SparePart $sparePart)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'category' => 'nullable|string|max:50',
            'unit' => 'required|string|max:20',
            'quantity_in_stock' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:0',
            'unit_cost' => 'required|numeric|min:0',
            'unit_price' => 'required|numeric|min:0',
        ]);

        $sparePart->update($data);

        return redirect()->route('inventory.index')->with('success', 'Spare part updated.');
    }

    /** Manual stock restock entry (e.g. after a supplier delivery). */
    public function restock(Request $request, SparePart $sparePart)
    {
        $data = $request->validate(['quantity' => 'required|integer|min:1']);

        $sparePart->increment('quantity_in_stock', $data['quantity']);

        return back()->with('success', "Added {$data['quantity']} {$sparePart->unit} of {$sparePart->name} to stock.");
    }

    public function destroy(SparePart $sparePart)
    {
        $sparePart->delete();

        return redirect()->route('inventory.index')->with('success', 'Spare part removed.');
    }
}
