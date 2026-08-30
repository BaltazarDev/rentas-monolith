<?php

namespace App\Http\Controllers;

use App\Models\House;
use App\Models\Unit;
use Illuminate\Http\Request;

class HouseController extends Controller
{
    public function index()
    {
        $houses = House::withCount([
            'units as total_units_count',
            'units as occupied_units_count' => function ($query) {
                $query->where('status', 'occupied');
            }
        ])->get();
        return view('houses.index', compact('houses'));
    }

    public function create()
    {
        return view('houses.create');
    }

    public function store(Request $request)
    {
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

        $photoUrl = 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?auto=format&fit=crop&w=600&q=80';
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
        return view('houses.edit', compact('house'));
    }

    public function update(Request $request, House $house)
    {
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

    public function destroy(House $house)
    {
        $house->delete();
        return redirect()->route('houses.index')->with('success', 'Propiedad eliminada con éxito.');
    }
}
