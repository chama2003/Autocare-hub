<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Helvetica, Arial, sans-serif; font-size: 12px; color: #1e293b; }
        h1 { font-size: 20px; margin-bottom: 0; }
        .muted { color: #64748b; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { padding: 6px 4px; border-bottom: 1px solid #e2e8f0; text-align: left; }
        .text-right { text-align: right; }
        .totals { width: 260px; float: right; margin-top: 10px; }
        .totals div { display: flex; justify-content: space-between; padding: 3px 0; }
        .grand { font-weight: bold; border-top: 1px solid #1e293b; padding-top: 6px; font-size: 14px; }
    </style>
</head>
<body>
    <h1>AutoCare Hub</h1>
    <p class="muted">Vehicle Service &amp; Maintenance Invoice</p>

    <table style="margin-top:20px;">
        <tr>
            <td><strong>Invoice #:</strong> {{ $invoice->invoice_number }}</td>
            <td><strong>Date:</strong> {{ $invoice->created_at->format('d M Y') }}</td>
        </tr>
        <tr>
            <td><strong>Customer:</strong> {{ $jobCard->vehicle->owner->name }}</td>
            <td><strong>Job Card:</strong> {{ $jobCard->job_card_number }}</td>
        </tr>
        <tr>
            <td colspan="2"><strong>Vehicle:</strong> {{ $jobCard->vehicle->plate_number }} — {{ $jobCard->vehicle->make }} {{ $jobCard->vehicle->model }} ({{ $jobCard->vehicle->manufacture_year }})</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr><th>Description</th><th class="text-right">Amount (Rs.)</th></tr>
        </thead>
        <tbody>
            @foreach($jobCard->serviceItems as $item)
            <tr><td>Service: {{ $item->serviceType->name }}</td><td class="text-right">{{ number_format($item->cost, 2) }}</td></tr>
            @endforeach
            @foreach($jobCard->partsUsed as $part)
            <tr><td>Part: {{ $part->sparePart->name }} x {{ $part->quantity }}</td><td class="text-right">{{ number_format($part->lineTotal(), 2) }}</td></tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <div><span>Labor Total</span><span>{{ number_format($invoice->labor_total, 2) }}</span></div>
        <div><span>Parts Total</span><span>{{ number_format($invoice->parts_total, 2) }}</span></div>
        <div><span>Tax</span><span>{{ number_format($invoice->tax_amount, 2) }}</span></div>
        <div><span>Discount</span><span>-{{ number_format($invoice->discount_amount, 2) }}</span></div>
        <div class="grand"><span>Grand Total</span><span>Rs. {{ number_format($invoice->grand_total, 2) }}</span></div>
    </div>
</body>
</html>
