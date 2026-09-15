<?php

namespace App\Http\Controllers;

use App\Models\House;
use App\Models\Unit;
use App\Models\Tenant;
use App\Models\Payment;
use App\Models\Expense;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->check() && !auth()->user()->can('dashboard.view')) {
            if (auth()->user()->can('houses.view')) {
                return redirect()->route('houses.index');
            }
            if (auth()->user()->can('tenants.view')) {
                return redirect()->route('tenants.index');
            }
            abort(403, 'Acceso denegado. No tienes permisos para acceder al Dashboard.');
        }

        $monthInput = $request->query('month');
        try {
            $selectedDate = $monthInput ? Carbon::createFromFormat('Y-m', $monthInput)->startOfMonth() : Carbon::now()->startOfMonth();
        } catch (\Exception $e) {
            $selectedDate = Carbon::now()->startOfMonth();
        }

        $startOfMonth = $selectedDate->copy()->startOfMonth();
        $endOfMonth = $selectedDate->copy()->endOfMonth();
        $selectedMonth = $selectedDate->format('Y-m');
        $prevMonth = $selectedDate->copy()->subMonth()->format('Y-m');
        $nextMonth = $selectedDate->copy()->addMonth()->format('Y-m');
        $isCurrentMonth = $selectedDate->isCurrentMonth() && $selectedDate->isCurrentYear();

        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];
        $formattedMonthName = $meses[(int)$selectedDate->format('n')] . ' ' . $selectedDate->format('Y');

        // Count metrics (Overall)
        $totalHouses = House::active()->count();
        $totalUnits = Unit::count();
        $occupiedUnits = Unit::where('status', 'occupied')->count();
        $vacantUnits = Unit::where('status', 'vacant')->count();
        $activeTenants = Tenant::where('is_active', true)->count();

        // Financial metrics (Selected Month)
        $incomeMonth = (float) Payment::where('status', 'paid')
            ->whereBetween('payment_date', [$startOfMonth->format('Y-m-d'), $endOfMonth->format('Y-m-d')])
            ->sum('amount');

        $expensesMonth = (float) Expense::whereBetween('expense_date', [$startOfMonth->format('Y-m-d'), $endOfMonth->format('Y-m-d')])
            ->sum('amount');

        $netProfitMonth = $incomeMonth - $expensesMonth;

        // Daily charts data for Selected Month
        $daysInMonth = $selectedDate->daysInMonth;
        $chartLabels = [];
        $chartIncomeData = array_fill(1, $daysInMonth, 0);
        $chartExpenseData = array_fill(1, $daysInMonth, 0);

        for ($d = 1; $d <= $daysInMonth; $d++) {
            $chartLabels[] = (string) $d;
        }

        // Daily Income
        $dailyPayments = Payment::where('status', 'paid')
            ->whereBetween('payment_date', [$startOfMonth->format('Y-m-d'), $endOfMonth->format('Y-m-d')])
            ->get();

        foreach ($dailyPayments as $p) {
            $day = (int) Carbon::parse($p->payment_date)->format('j');
            if (isset($chartIncomeData[$day])) {
                $chartIncomeData[$day] += (float) $p->amount;
            }
        }

        // Daily Expenses
        $dailyExpenses = Expense::whereBetween('expense_date', [$startOfMonth->format('Y-m-d'), $endOfMonth->format('Y-m-d')])
            ->get();

        foreach ($dailyExpenses as $e) {
            $day = (int) Carbon::parse($e->expense_date)->format('j');
            if (isset($chartExpenseData[$day])) {
                $chartExpenseData[$day] += (float) $e->amount;
            }
        }

        // Income by House (for Doughnut chart)
        $houses = House::with(['units.payments' => function ($q) use ($startOfMonth, $endOfMonth) {
            $q->where('status', 'paid')
              ->whereBetween('payment_date', [$startOfMonth->format('Y-m-d'), $endOfMonth->format('Y-m-d')]);
        }])->get();

        $houseLabels = [];
        $houseIncomes = [];

        foreach ($houses as $h) {
            $hIncome = 0;
            foreach ($h->units as $u) {
                $hIncome += $u->payments->sum('amount');
            }
            if ($hIncome > 0) {
                $houseLabels[] = $h->name;
                $houseIncomes[] = round($hIncome, 2);
            }
        }

        // Debts and Pending Payments for the Selected Month
        $tenants = Tenant::with(['unit.house'])
            ->where('start_date', '<=', $endOfMonth->format('Y-m-d'))
            ->where(function ($q) use ($startOfMonth) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', $startOfMonth->format('Y-m-d'));
            })
            ->get();

        $pendingPayments = [];
        $today = Carbon::now();

        foreach ($tenants as $tenant) {
            if (!$tenant->unit) continue;

            $paidRent = (float) Payment::where('unit_id', $tenant->unit_id)
                ->where('type', 'rent')
                ->where('status', 'paid')
                ->whereBetween('payment_date', [$startOfMonth->format('Y-m-d'), $endOfMonth->format('Y-m-d')])
                ->sum('amount');

            $expectedRent = (float) $tenant->unit->base_rent_cost;
            $debt = max(0, $expectedRent - $paidRent);

            if ($debt > 0) {
                $effectiveDueDay = min($tenant->payment_due_day, $daysInMonth);
                $dueDate = $selectedDate->copy()->day($effectiveDueDay);

                $status = 'pending';
                if ($paidRent > 0) {
                    $status = 'partial';
                } elseif ($today->gt($dueDate)) {
                    $status = 'overdue';
                }

                $pendingPayments[] = [
                    'tenant_name' => $tenant->full_name,
                    'phone' => $tenant->phone,
                    'house_name' => $tenant->unit->house->name ?? 'Casa',
                    'unit_name' => $tenant->unit->name ?? 'Unidad',
                    'due_day' => $effectiveDueDay,
                    'due_date' => $dueDate->format('d/m/Y'),
                    'rent_cost' => $expectedRent,
                    'paid_amount' => $paidRent,
                    'debt_amount' => $debt,
                    'status' => $status,
                    'unit_id' => $tenant->unit_id,
                ];
            }
        }

        $totalDebtsMonth = collect($pendingPayments)->sum('debt_amount');

        // Recent Transactions from selected month
        $payments = Payment::with(['unit.house', 'unit.tenant'])
            ->whereBetween('payment_date', [$startOfMonth->format('Y-m-d'), $endOfMonth->format('Y-m-d')])
            ->orderBy('payment_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->map(function ($item) {
                $tenantName = ($item->unit && $item->unit->tenant) ? ' (' . $item->unit->tenant->full_name . ')' : '';
                return [
                    'id' => $item->id,
                    'type' => 'income',
                    'category' => $item->type === 'rent' ? 'Renta' : 'Servicios',
                    'amount' => $item->amount,
                    'date' => $item->payment_date,
                    'title' => ($item->unit->house->name ?? 'Casa') . ' - ' . ($item->unit->name ?? 'Unidad') . $tenantName,
                    'notes' => $item->notes,
                ];
            });

        $expenses = Expense::with(['house', 'unit'])
            ->whereBetween('expense_date', [$startOfMonth->format('Y-m-d'), $endOfMonth->format('Y-m-d')])
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

        // Convert chart data to zero-indexed arrays for json
        $chartIncomeData = array_values($chartIncomeData);
        $chartExpenseData = array_values($chartExpenseData);

        return view('dashboard', compact(
            'totalHouses', 'totalUnits', 'occupiedUnits', 'vacantUnits', 'activeTenants',
            'incomeMonth', 'expensesMonth', 'netProfitMonth', 'totalDebtsMonth',
            'recentTransactions', 'pendingPayments',
            'selectedMonth', 'prevMonth', 'nextMonth', 'isCurrentMonth', 'formattedMonthName',
            'chartLabels', 'chartIncomeData', 'chartExpenseData',
            'houseLabels', 'houseIncomes'
        ));
    }
}
