<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    /**
     * Revenue & Expense dashboard for workshop owners: daily/monthly income,
     * operational expenses, and month-by-month profitability trend for charting.
     */
    public function dashboard(Request $request)
    {
        $year = $request->integer('year', now()->year);
        $driver = DB::getDriverName();

        $monthExpression = match ($driver) {
            'sqlite' => "CAST(strftime('%m', created_at) AS INTEGER)",
            default => 'MONTH(created_at)',
        };

        $expenseMonthExpression = match ($driver) {
            'sqlite' => "CAST(strftime('%m', expense_date) AS INTEGER)",
            default => 'MONTH(expense_date)',
        };

        $monthlyRevenue = Invoice::selectRaw($monthExpression . ' as month, SUM(grand_total) as total')
            ->where('status', 'paid')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->pluck('total', 'month');

        $monthlyExpenses = Expense::selectRaw($expenseMonthExpression . ' as month, SUM(amount) as total')
            ->whereYear('expense_date', $year)
            ->groupBy('month')
            ->pluck('total', 'month');

        $months = collect(range(1, 12))->map(fn ($m) => [
            'month' => $m,
            'label' => \Carbon\Carbon::create()->month($m)->format('M'),
            'revenue' => (float) ($monthlyRevenue[$m] ?? 0),
            'expenses' => (float) ($monthlyExpenses[$m] ?? 0),
        ]);

        $todayRevenue = Invoice::where('status', 'paid')->whereDate('created_at', now()->toDateString())->sum('grand_total');
        $monthRevenue = $months->firstWhere('month', now()->month)['revenue'] ?? 0;
        $monthExpenses = $months->firstWhere('month', now()->month)['expenses'] ?? 0;

        $expensesByCategory = Expense::selectRaw('category, SUM(amount) as total')
            ->whereYear('expense_date', $year)
            ->groupBy('category')
            ->pluck('total', 'category');

        return view('finance.dashboard', compact('months', 'todayRevenue', 'monthRevenue', 'monthExpenses', 'expensesByCategory', 'year'));
    }

    public function expenses(Request $request)
    {
        $expenses = Expense::with('recorder')->latest('expense_date')->paginate(20);

        return view('finance.expenses', compact('expenses'));
    }

    public function storeExpense(Request $request)
    {
        $data = $request->validate([
            'category' => 'required|in:spare_parts_purchase,utilities,salary,rent,other',
            'title' => 'required|string|max:150',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        $data['recorded_by'] = auth()->id();
        Expense::create($data);

        return back()->with('success', 'Expense recorded.');
    }
}
