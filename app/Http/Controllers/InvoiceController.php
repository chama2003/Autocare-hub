<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\JobCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Invoice::with(['jobCard.vehicle', 'customer']);

        if ($user->isCustomer()) {
            $query->where('user_id', $user->id);
        }

        $invoices = $query->latest()->paginate(20);

        return view('invoices.index', compact('invoices'));
    }

    /**
     * Generate (or regenerate) the invoice + PDF for a completed job card.
     * Costs are compiled automatically from the job card's logged services and parts,
     * applying a flat tax rate and any manual discount.
     */
    public function generate(Request $request, JobCard $jobCard)
    {
        abort_if($jobCard->status !== 'completed', 422, 'Job card must be completed before invoicing.');

        $data = $request->validate([
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
        ]);

        $laborTotal = $jobCard->laborTotal();
        $partsTotal = $jobCard->partsTotal();
        $subtotal = $laborTotal + $partsTotal;
        $taxRate = $data['tax_rate'] ?? 0;
        $taxAmount = round($subtotal * ($taxRate / 100), 2);
        $discount = $data['discount_amount'] ?? 0;
        $grandTotal = max(0, $subtotal + $taxAmount - $discount);

        $invoice = $jobCard->invoice()->updateOrCreate(
            ['job_card_id' => $jobCard->id],
            [
                'invoice_number' => $jobCard->invoice?->invoice_number
                    ?? ('INV-' . now()->format('Y') . '-' . str_pad((string) (Invoice::count() + 1), 4, '0', STR_PAD_LEFT)),
                'user_id' => $jobCard->vehicle->user_id,
                'labor_total' => $laborTotal,
                'parts_total' => $partsTotal,
                'tax_amount' => $taxAmount,
                'discount_amount' => $discount,
                'grand_total' => $grandTotal,
                'status' => 'unpaid',
            ]
        );

        $pdf = Pdf::loadView('pdf.invoice', [
            'invoice' => $invoice,
            'jobCard' => $jobCard->load(['vehicle.owner', 'serviceItems.serviceType', 'partsUsed.sparePart']),
        ]);

        $path = 'invoices/' . $invoice->invoice_number . '.pdf';
        Storage::disk('public')->put($path, $pdf->output());
        $invoice->update(['pdf_path' => $path]);

        return redirect()->route('invoices.show', $invoice)->with('success', 'Invoice generated.');
    }

    public function show(Invoice $invoice)
    {
        $user = Auth::user();
        abort_if($user->isCustomer() && $invoice->user_id !== $user->id, 403);

        $invoice->load(['jobCard.vehicle.owner', 'jobCard.serviceItems.serviceType', 'jobCard.partsUsed.sparePart']);

        return view('invoices.show', compact('invoice'));
    }

    public function download(Invoice $invoice)
    {
        $user = Auth::user();
        abort_if($user->isCustomer() && $invoice->user_id !== $user->id, 403);
        abort_if(! $invoice->pdf_path, 404);

        return Storage::disk('public')->download($invoice->pdf_path, $invoice->invoice_number . '.pdf');
    }

    public function markPaid(Invoice $invoice)
    {
        $invoice->update(['status' => 'paid']);

        return back()->with('success', 'Invoice marked as paid.');
    }
}
