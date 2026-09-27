<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\JobCard;
use App\Models\JobCardPart;
use App\Models\JobCardServiceItem;
use App\Models\ServiceType;
use App\Models\SparePart;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JobCardController extends Controller
{
    public function index(Request $request)
    {
        $query = JobCard::with(['vehicle', 'mechanic']);

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if (Auth::user()->isMechanic()) {
            $query->where('mechanic_id', Auth::id());
        }

        $jobCards = $query->latest('opened_at')->paginate(20)->withQueryString();

        return view('jobcards.index', compact('jobCards'));
    }

    public function create(Request $request)
    {
        $vehicles = Vehicle::orderBy('plate_number')->get();
        $mechanics = User::where('role', 'mechanic')->get();
        $appointment = $request->filled('appointment_id')
            ? Appointment::find($request->integer('appointment_id'))
            : null;

        return view('jobcards.create', compact('vehicles', 'mechanics', 'appointment'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'mechanic_id' => 'nullable|exists:users,id',
            'mileage_at_service_km' => 'required|integer|min:0',
            'customer_complaint' => 'nullable|string|max:2000',
            'labor_rate_per_hour' => 'required|numeric|min:0',
        ]);

        $jobCard = DB::transaction(function () use ($data) {
            $data['created_by'] = Auth::id();
            $data['job_card_number'] = 'JC-' . now()->format('Y') . '-' . str_pad((string) (JobCard::count() + 1), 4, '0', STR_PAD_LEFT);
            $data['status'] = 'pending';
            $data['opened_at'] = now();

            $jobCard = JobCard::create($data);

            if (! empty($data['appointment_id'])) {
                Appointment::where('id', $data['appointment_id'])->update(['status' => 'completed']);
            }

            return $jobCard;
        });

        return redirect()->route('jobcards.show', $jobCard)->with('success', 'Job card opened: ' . $jobCard->job_card_number);
    }

    public function show(JobCard $jobCard)
    {
        $jobCard->load(['vehicle.owner', 'mechanic', 'serviceItems.serviceType', 'partsUsed.sparePart', 'invoice']);

        $serviceTypes = ServiceType::orderBy('name')->get();
        $spareParts = SparePart::orderBy('name')->get();

        return view('jobcards.show', compact('jobCard', 'serviceTypes', 'spareParts'));
    }

    /** Update the operational status of a job card (Pending/In Progress/Quality Check/Completed). */
    public function updateStatus(Request $request, JobCard $jobCard)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,in_progress,quality_check,completed',
            'diagnosis' => 'nullable|string|max:2000',
            'labor_hours' => 'nullable|numeric|min:0',
        ]);

        $jobCard->fill($data);

        if ($data['status'] === 'completed') {
            $jobCard->closed_at = now();

            // Reflect the service back onto the vehicle so future "due for service" checks are accurate.
            $jobCard->vehicle->update([
                'current_mileage_km' => max($jobCard->vehicle->current_mileage_km, $jobCard->mileage_at_service_km ?? 0),
                'last_service_date' => now(),
                'last_service_mileage_km' => $jobCard->mileage_at_service_km,
            ]);
        }

        $jobCard->save();

        return back()->with('success', 'Job card status updated to ' . str_replace('_', ' ', $data['status']) . '.');
    }

    /** Log a performed service (labor) line item on the job card. */
    public function addServiceItem(Request $request, JobCard $jobCard)
    {
        $data = $request->validate([
            'service_type_id' => 'required|exists:service_types,id',
            'cost' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        $data['job_card_id'] = $jobCard->id;
        JobCardServiceItem::create($data);

        return back()->with('success', 'Service logged on job card.');
    }

    /**
     * Assign a spare part to the job card. This automatically deducts the
     * quantity from workshop inventory in the same DB transaction.
     */
    public function addPart(Request $request, JobCard $jobCard)
    {
        $data = $request->validate([
            'spare_part_id' => 'required|exists:spare_parts,id',
            'quantity' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($data, $jobCard) {
            $sparePart = SparePart::lockForUpdate()->findOrFail($data['spare_part_id']);
            $sparePart->deductStock($data['quantity']);

            JobCardPart::create([
                'job_card_id' => $jobCard->id,
                'spare_part_id' => $sparePart->id,
                'quantity' => $data['quantity'],
                'unit_price' => $sparePart->unit_price,
            ]);
        });

        return back()->with('success', 'Spare part assigned and stock updated.');
    }
}
