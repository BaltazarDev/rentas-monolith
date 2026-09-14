<?php

namespace App\Http\Controllers;

use App\Models\House;
use App\Models\Payment;
use App\Models\Expense;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : Carbon::now()->startOfMonth();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : Carbon::now()->endOfMonth();
        $houseId = $request->input('house_id');
        $type = $request->input('type'); // 'income', 'expense', or null (all)

        // 1. Fetch Income (Payments)
        $paymentsQuery = Payment::with(['unit.house', 'unit.tenant'])
            ->whereBetween('payment_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);

        if ($houseId) {
            $paymentsQuery->whereHas('unit', function ($query) use ($houseId) {
                $query->where('house_id', $houseId);
            });
        }

        $payments = [];
        if (!$type || $type === 'income') {
            $payments = $paymentsQuery->get()->map(function ($item) {
                $tenantName = ($item->unit && $item->unit->tenant) ? ' (' . $item->unit->tenant->full_name . ')' : '';
                return [
                    'id' => $item->id,
                    'tx_type' => 'income',
                    'category' => $item->type === 'rent' ? 'Renta' : 'Servicios',
                    'amount' => $item->amount,
                    'date' => $item->payment_date,
                    'title' => ($item->unit->house->name ?? 'Casa') . ' - ' . ($item->unit->name ?? 'Unidad') . $tenantName,
                    'notes' => $item->notes,
                    'receipt_url' => $item->receipt_url,
                ];
            });
        }

        // 2. Fetch Expenses
        $expensesQuery = Expense::with('house', 'unit')
            ->whereBetween('expense_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);

        if ($houseId) {
            $expensesQuery->where(function($query) use ($houseId) {
                $query->where('house_id', $houseId)
                      ->orWhereHas('unit', function($q) use ($houseId) {
                          $q->where('house_id', $houseId);
                      });
            });
        }

        $expenses = [];
        if (!$type || $type === 'expense') {
            $expenses = $expensesQuery->get()->map(function ($item) {
                $title = 'Gasto General';
                if ($item->house) {
                    $title = $item->house->name;
                    if ($item->unit) {
                        $title .= ' - ' . $item->unit->name;
                    }
                }
                return [
                    'id' => $item->id,
                    'tx_type' => 'expense',
                    'category' => $item->type,
                    'amount' => $item->amount,
                    'date' => $item->expense_date,
                    'title' => $title,
                    'notes' => $item->notes,
                    'receipt_url' => $item->receipt_url,
                ];
            });
        }

        // 3. Combine and sort transactions
        $transactionsCollection = collect($payments)->concat($expenses)->sortByDesc('date');

        // Calculate totals for the filtered period
        $totalIncome = collect($payments)->sum('amount');
        $totalExpense = collect($expenses)->sum('amount');
        $netBalance = $totalIncome - $totalExpense;

        // 4. Manually paginate the combined collection
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 15;
        $currentPageItems = $transactionsCollection->slice(($currentPage - 1) * $perPage, $perPage)->all();
        
        $transactions = new LengthAwarePaginator(
            $currentPageItems,
            $transactionsCollection->count(),
            $perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath(), 'query' => $request->query()]
        );

        // Fetch houses list for the filter dropdown
        $houses = House::orderBy('name')->get();

        return view('transactions.index', compact(
            'transactions', 'totalIncome', 'totalExpense', 'netBalance', 
            'houses', 'startDate', 'endDate', 'houseId', 'type'
        ));
    }
}
