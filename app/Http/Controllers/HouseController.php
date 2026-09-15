<?php

namespace App\Http\Controllers;

use App\Models\House;
use App\Models\Unit;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HouseController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(auth()->check() && auth()->user()->can('houses.view'), 403, 'Acceso denegado. No tienes permisos para ver propiedades.');

        $status = $request->query('status', 'active');
        if (!auth()->user()->can('houses.delete')) {
            $status = 'active';
        }

        $activeCount = House::active()->count();
        $archivedCount = House::archived()->count();

        $query = ($status === 'archived') ? House::archived() : House::active();

        $houses = $query->withCount([
            'units as total_units_count',
            'units as occupied_units_count' => function ($query) {
                $query->where('status', 'occupied');
            }
        ])->get();

        return view('houses.index', compact('houses', 'status', 'activeCount', 'archivedCount'));
    }

    public function create()
    {
        abort_unless(auth()->check() && auth()->user()->can('houses.create'), 403, 'Acceso denegado. No tienes permisos para crear propiedades.');
        return view('houses.create');
    }

    public function store(Request $request)
    {
        abort_unless(auth()->check() && auth()->user()->can('houses.create'), 403, 'Acceso denegado. No tienes permisos para crear propiedades.');

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'map_url' => 'nullable|url',
            'embed_map_url' => 'nullable|url',
            'units' => 'nullable|array',
            'units.*.name' => 'required|string|max:255',
            'units.*.type' => 'required|string|in:apartment,commercial,house',
            'units.*.base_rent_cost' => 'required|numeric|min:0',
        ]);

        $photoUrl = null;
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('houses', 'public');
            $photoUrl = '/storage/' . $path;
        }

        $house = House::create([
            'name' => $request->name,
            'address' => $request->address,
            'description' => $request->description,
            'photo_url' => $photoUrl,
            'map_url' => $request->map_url,
            'embed_map_url' => $request->embed_map_url,
        ]);

        if ($request->has('units') && is_array($request->units)) {
            foreach ($request->units as $unitData) {
                $house->units()->create([
                    'name' => $unitData['name'],
                    'type' => $unitData['type'],
                    'base_rent_cost' => $unitData['base_rent_cost'],
                    'status' => 'vacant',
                ]);
            }
        }

        return redirect()->route('houses.index')->with('success', 'Propiedad creada con éxito.');
    }

    public function show(House $house)
    {
        $house->load(['units.tenant', 'expenses' => function ($query) {
            $query->orderBy('expense_date', 'desc');
        }]);
        return view('houses.show', compact('house'));
    }

    public function edit(House $house)
    {
        abort_unless(auth()->check() && auth()->user()->can('houses.edit'), 403, 'Acceso denegado. No tienes permisos para editar propiedades.');
        return view('houses.edit', compact('house'));
    }

    public function update(Request $request, House $house)
    {
        abort_unless(auth()->check() && auth()->user()->can('houses.edit'), 403, 'Acceso denegado. No tienes permisos para editar propiedades.');

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'map_url' => 'nullable|url',
            'embed_map_url' => 'nullable|url',
        ]);

        $photoUrl = $house->photo_url;
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('houses', 'public');
            $photoUrl = '/storage/' . $path;
        }

        $house->update([
            'name' => $request->name,
            'address' => $request->address,
            'description' => $request->description,
            'photo_url' => $photoUrl,
            'map_url' => $request->map_url,
            'embed_map_url' => $request->embed_map_url,
        ]);

        return redirect()->route('houses.show', $house)->with('success', 'Propiedad actualizada con éxito.');
    }

    public function archive(House $house)
    {
        abort_unless(auth()->check() && auth()->user()->can('houses.delete'), 403, 'No tienes permisos para archivar propiedades.');

        $house->update(['is_archived' => true]);

        return redirect()->route('houses.index')->with('success', 'Propiedad archivada con éxito. Todos sus datos históricos se mantienen seguros.');
    }

    public function unarchive(House $house)
    {
        abort_unless(auth()->check() && auth()->user()->can('houses.delete'), 403, 'No tienes permisos para desarchivar propiedades.');

        $house->update(['is_archived' => false]);

        return redirect()->route('houses.show', $house)->with('success', 'Propiedad restaurada y activa nuevamente.');
    }

    public function destroy(House $house)
    {
        abort_unless(auth()->check() && (auth()->user()->isSuperAdmin() || auth()->user()->can('houses.delete')), 403, 'No tienes permisos para eliminar propiedades.');

        try {
            DB::transaction(function () use ($house) {
                // Eliminar unidades y sus relaciones (pagos, gastos, inquilinos)
                foreach ($house->units as $unit) {
                    Tenant::where('unit_id', $unit->id)->update([
                        'unit_id' => null,
                        'is_active' => false
                    ]);
                    $unit->payments()->delete();
                    $unit->expenses()->delete();
                    $unit->delete();
                }

                // Eliminar gastos directos de la propiedad
                $house->expenses()->delete();

                // Eliminar la propiedad
                $house->delete();
            });

            return redirect()->route('houses.index')->with('success', 'Propiedad y todas sus unidades eliminadas con éxito.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar la propiedad: ' . $e->getMessage());
        }
    }
}
