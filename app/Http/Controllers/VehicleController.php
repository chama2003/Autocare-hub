<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VehicleController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $vehicles = $user->isCustomer()
            ? $user->vehicles()->latest()->get()
            : Vehicle::with('owner')->latest()->paginate(20);

        return view('vehicles.index', compact('vehicles'));
    }

    public function create()
    {
        return view('vehicles.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'plate_number' => 'required|string|max:20|unique:vehicles,plate_number',
            'make' => 'required|string|max:50',
            'model' => 'required|string|max:50',
            'type' => 'required|in:car,bike,van,truck',
            'manufacture_year' => 'required|integer|min:1970|max:' . (date('Y') + 1),
            'color' => 'nullable|string|max:30',
            'current_mileage_km' => 'required|integer|min:0',
            'service_interval_km' => 'nullable|integer|min:500',
            'service_interval_months' => 'nullable|integer|min:1',
        ]);

        $data['user_id'] = Auth::id();

        $vehicle = Vehicle::create($data);

        return redirect()->route('vehicles.show', $vehicle)
            ->with('success', 'Vehicle registered successfully.');
    }

    public function show(Vehicle $vehicle)
    {
        $this->authorizeAccess($vehicle);

        $vehicle->load(['serviceHistory']);

        return view('vehicles.show', compact('vehicle'));
    }

    public function edit(Vehicle $vehicle)
    {
        $this->authorizeAccess($vehicle);

        return view('vehicles.edit', compact('vehicle'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $this->authorizeAccess($vehicle);

        $data = $request->validate([
            'make' => 'required|string|max:50',
            'model' => 'required|string|max:50',
            'type' => 'required|in:car,bike,van,truck',
            'manufacture_year' => 'required|integer|min:1970|max:' . (date('Y') + 1),
            'color' => 'nullable|string|max:30',
            'current_mileage_km' => 'required|integer|min:0',
            'service_interval_km' => 'nullable|integer|min:500',
            'service_interval_months' => 'nullable|integer|min:1',
        ]);

        $vehicle->update($data);

        return redirect()->route('vehicles.show', $vehicle)->with('success', 'Vehicle updated.');
    }

    public function destroy(Vehicle $vehicle)
    {
        $this->authorizeAccess($vehicle);
        $vehicle->delete();

        return redirect()->route('vehicles.index')->with('success', 'Vehicle removed.');
    }

    /**
     * Customers may only touch their own vehicles; staff/admin/mechanic may touch any.
     */
    private function authorizeAccess(Vehicle $vehicle): void
    {
        $user = Auth::user();

        if ($user->isCustomer() && $vehicle->user_id !== $user->id) {
            abort(403);
        }
    }
}
