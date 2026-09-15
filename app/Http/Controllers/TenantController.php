<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Tenant;
use App\Models\Payment;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function index()
    {
        abort_unless(auth()->check() && auth()->user()->can('tenants.view'), 403, 'Acceso denegado. No tienes permisos para ver inquilinos.');

        $query = Tenant::with('unit.house')->orderBy('full_name');
        if (!auth()->user()->can('tenants.view_all')) {
            $query->where('is_active', true);
        }
        $tenants = $query->get();
        return view('tenants.index', compact('tenants'));
    }

    public function create(Request $request)
    {
        abort_unless(auth()->check() && auth()->user()->can('tenants.create'), 403, 'Acceso denegado. No tienes permisos para registrar nuevos inquilinos.');
        $unit_id = $request->query('unit_id');
        // Get all units that are either vacant, or if it is the requested unit_id
        $units = Unit::with('house')->where('status', 'vacant')
            ->orWhere('id', $unit_id)
            ->get();
        return view('tenants.create', compact('units', 'unit_id'));
    }

    public function store(Request $request)
    {
        abort_unless(auth()->check() && auth()->user()->can('tenants.create'), 403, 'Acceso denegado. No tienes permisos para registrar nuevos inquilinos.');

        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'full_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'payment_due_day' => 'required|integer|between:1,31',
            'notes' => 'nullable|string',
        ]);

        $tenant = Tenant::create([
            'unit_id' => $request->unit_id,
            'full_name' => $request->full_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'payment_due_day' => $request->payment_due_day,
            'notes' => $request->notes,
            'is_active' => true,
        ]);

        // Update unit status to occupied
        Unit::where('id', $request->unit_id)->update(['status' => 'occupied']);

        return redirect()->route('units.show', $request->unit_id)->with('success', 'Inquilino registrado y asignado con éxito.');
    }

    public function edit(Tenant $tenant)
    {
        abort_unless(auth()->check() && auth()->user()->can('tenants.edit'), 403, 'Acceso denegado. No tienes permisos para editar inquilinos.');
        $units = Unit::with('house')->where('status', 'vacant')
            ->orWhere('id', $tenant->unit_id)
            ->get();
        return view('tenants.edit', compact('tenant', 'units'));
    }

    public function update(Request $request, Tenant $tenant)
    {
        abort_unless(auth()->check() && auth()->user()->can('tenants.edit'), 403, 'Acceso denegado. No tienes permisos para editar inquilinos.');

        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'full_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'payment_due_day' => 'required|integer|between:1,31',
            'notes' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        $oldUnitId = $tenant->unit_id;

        $tenant->update([
            'unit_id' => $request->unit_id,
            'full_name' => $request->full_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'payment_due_day' => $request->payment_due_day,
            'notes' => $request->notes,
            'is_active' => $request->is_active,
        ]);

        // Handle unit status updates
        if (!$request->is_active) {
            // Free the unit if tenant is deactivated
            Unit::where('id', $oldUnitId)->update(['status' => 'vacant']);
        } else {
            if ($oldUnitId != $request->unit_id) {
                // Free the old unit and occupy the new one
                Unit::where('id', $oldUnitId)->update(['status' => 'vacant']);
                Unit::where('id', $request->unit_id)->update(['status' => 'occupied']);
            } else {
                // Ensure current unit is occupied
                Unit::where('id', $request->unit_id)->update(['status' => 'occupied']);
            }
        }

        return redirect()->route('tenants.index')->with('success', 'Inquilino actualizado con éxito.');
    }

    public function show(Tenant $tenant)
    {
        $tenant->load('unit.house');

        $payments = collect();
        if ($tenant->unit_id) {
            $payments = Payment::where('unit_id', $tenant->unit_id)
                ->where('payment_date', '>=', $tenant->start_date)
                ->when($tenant->end_date, function ($query) use ($tenant) {
                    $query->where('payment_date', '<=', $tenant->end_date);
                })
                ->orderBy('payment_date', 'desc')
                ->get();
        }

        return view('tenants.show', compact('tenant', 'payments'));
    }

    public function destroy(Tenant $tenant)
    {
        abort_unless(auth()->check() && auth()->user()->can('tenants.delete'), 403, 'Acceso denegado. No tienes permisos para eliminar inquilinos.');

        // Free the unit if tenant was active
        if ($tenant->unit_id) {
            Unit::where('id', $tenant->unit_id)->update(['status' => 'vacant']);
        }

        $tenant->update(['is_active' => false]);
        $tenant->delete(); // Soft delete

        return redirect()->route('tenants.index')->with('success', 'Inquilino eliminado con éxito.');
    }
}
