<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\ServiceType;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Appointment::with(['customer', 'vehicle', 'serviceType']);

        if ($user->isCustomer()) {
            $query->where('user_id', $user->id);
        } elseif ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        $appointments = $query->orderByDesc('appointment_date')
            ->orderByDesc('appointment_time')
            ->paginate(20)
            ->withQueryString();

        return view('appointments.index', compact('appointments'));
    }

    /** Simple JSON feed used by the Alpine.js calendar widget. */
    public function calendarFeed(Request $request)
    {
        $appointments = Appointment::with(['vehicle', 'serviceType'])
            ->whereIn('status', ['pending', 'approved'])
            ->get()
            ->map(fn (Appointment $a) => [
                'id' => $a->id,
                'title' => $a->serviceType->name . ' — ' . $a->vehicle->plate_number,
                'start' => $a->appointment_date->format('Y-m-d') . 'T' . $a->appointment_time,
                'status' => $a->status,
            ]);

        return response()->json($appointments);
    }

    public function create()
    {
        $user = Auth::user();
        $vehicles = $user->isCustomer() ? $user->vehicles()->get() : Vehicle::all();
        $serviceTypes = ServiceType::orderBy('name')->get();

        return view('appointments.create', compact('vehicles', 'serviceTypes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'service_type_id' => 'required|exists:service_types,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
            'notes' => 'nullable|string|max:1000',
        ]);

        $data['user_id'] = Auth::id();
        $data['status'] = 'pending';

        $appointment = Appointment::create($data);

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment requested for ' . $appointment->appointment_date->format('d M Y') . '.');
    }

    /** Staff/Admin action: approve a pending appointment. */
    public function approve(Appointment $appointment)
    {
        $appointment->update(['status' => 'approved', 'approved_by' => Auth::id()]);

        return back()->with('success', 'Appointment approved.');
    }

    /** Staff/Admin action: reschedule to a new date/time. */
    public function reschedule(Request $request, Appointment $appointment)
    {
        $data = $request->validate([
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
        ]);

        $appointment->update(array_merge($data, ['status' => 'rescheduled']));

        return back()->with('success', 'Appointment rescheduled.');
    }

    /** Staff/Admin action: cancel an appointment. */
    public function cancel(Appointment $appointment)
    {
        $appointment->update(['status' => 'cancelled']);

        return back()->with('success', 'Appointment cancelled.');
    }
}
