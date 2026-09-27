<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\JobCard;
use App\Models\SparePart;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Role-aware landing dashboard.
     * Customers see their own vehicles/appointments.
     * Staff/Admin/Mechanic see workshop-wide operational widgets.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->isCustomer()) {
            $vehicles = $user->vehicles()->withCount('jobCards')->get();
            $upcomingAppointments = $user->appointments()
                ->with(['vehicle', 'serviceType'])
                ->whereIn('status', ['pending', 'approved'])
                ->orderBy('appointment_date')
                ->get();

            return view('dashboard', [
                'vehicles' => $vehicles,
                'upcomingAppointments' => $upcomingAppointments,
                'dueForService' => $vehicles->filter->isServiceDue(),
            ]);
        }

        // Staff / Admin / Mechanic operational overview
        $todaysAppointments = Appointment::with(['customer', 'vehicle', 'serviceType'])
            ->whereDate('appointment_date', now()->toDateString())
            ->orderBy('appointment_time')
            ->get();

        $openJobCards = JobCard::with(['vehicle', 'mechanic'])
            ->whereIn('status', ['pending', 'in_progress', 'quality_check'])
            ->latest('opened_at')
            ->get();

        $lowStockParts = SparePart::whereColumn('quantity_in_stock', '<=', 'low_stock_threshold')->get();

        $monthRevenue = Invoice::where('status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('grand_total');

        return view('dashboard', [
            'todaysAppointments' => $todaysAppointments,
            'openJobCards' => $openJobCards,
            'lowStockParts' => $lowStockParts,
            'monthRevenue' => $monthRevenue,
        ]);
    }
}
