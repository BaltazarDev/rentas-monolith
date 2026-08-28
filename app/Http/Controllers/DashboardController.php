<?php

namespace App\Http\Controllers;

use App\Models\House;
use App\Models\Unit;
use App\Models\Tenant;
use App\Models\Payment;
use App\Models\Expense;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // Count metrics
        $totalHouses = House::count();
        $totalUnits = Unit::count();
        $occupiedUnits = Unit::where('status', 'occupied')->count();
        $vacantUnits = Unit::where('status', 'vacant')->count();
        $activeTenants = Tenant::where('is_active', true)->count();

        // Financial metrics (Current Month)
        $incomeMonth = Payment::where('status', 'paid')
            ->whereBetween('payment_date', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        $expensesMonth = Expense::whereBetween('expense_date', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        $netProfitMonth = $incomeMonth - $expensesMonth;

        // Recent Transactions (Combined payments and expenses)
        $payments = Payment::with('unit.house')
            ->orderBy('payment_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'type' => 'income',
                    'category' => $item->type === 'rent' ? 'Renta' : 'Servicios',
                    'amount' => $item->amount,
                    'date' => $item->payment_date,
                    'title' => $item->unit->name . ' - ' . $item->unit->house->name,
                    'notes' => $item->notes,
                ];
            });

        $expenses = Expense::with('house', 'unit')
            ->orderBy('expense_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->map(function ($item) {
                $title = 'Gasto General';
                if ($item->house) {
                    $title = $item->house->name;
                    if ($item->unit) {
                        $title .= ' - ' . $item->unit->name;
                    }
                }
                return [
                    'id' => $item->id,
                    'type' => 'expense',
                    'category' => $item->type,
                    'amount' => $item->amount,
                    'date' => $item->expense_date,
                    'title' => $title,
                    'notes' => $item->notes,
                ];
            });

        $recentTransactions = $payments->concat($expenses)
            ->sortByDesc('date')
            ->take(8);

        // Pending payments (Tenants who haven't paid this month's rent)
        $tenants = Tenant::with('unit.house')->where('is_active', true)->get();
        $pendingPayments = [];

        foreach ($tenants as $tenant) {
            $hasPaidRentThisMonth = Payment::where('unit_id', $tenant->unit_id)
                ->where('type', 'rent')
                ->where('status', 'paid')
                ->whereBetween('payment_date', [$startOfMonth, $endOfMonth])
                ->exists();

            if (!$hasPaidRentThisMonth) {
                $pendingPayments[] = [
                    'tenant_name' => $tenant->full_name,
                    'house_name' => $tenant->unit->house->name ?? '',
                    'unit_name' => $tenant->unit->name ?? '',
                    'due_day' => $tenant->payment_due_day,
                    'rent_cost' => $tenant->unit->base_rent_cost ?? 0,
                    'unit_id' => $tenant->unit_id,
                ];
            }
        }

        return view('dashboard', compact(
            'totalHouses', 'totalUnits', 'occupiedUnits', 'vacantUnits', 'activeTenants',
            'incomeMonth', 'expensesMonth', 'netProfitMonth', 'recentTransactions', 'pendingPayments'
        ));
    }
}
