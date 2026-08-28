<?php

namespace App\Http\Controllers;

use App\Models\House;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function create(Request $request)
    {
        $house_id = $request->query('house_id');
        $houses = House::all();
        return view('units.create', compact('houses', 'house_id'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'house_id' => 'required|exists:houses,id',
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:apartment,commercial,house',
            'base_rent_cost' => 'required|numeric|min:0',
        ]);

        $unit = Unit::create([
            'house_id' => $request->house_id,
            'name' => $request->name,
            'type' => $request->type,
            'base_rent_cost' => $request->base_rent_cost,
            'status' => 'vacant',
        ]);

        return redirect()->route('houses.show', $request->house_id)->with('success', 'Unidad agregada con éxito.');
    }

    public function show(Unit $unit)
    {
        $unit->load(['house', 'tenant', 'payments' => function ($query) {
            $query->orderBy('payment_date', 'desc');
        }]);
        return view('units.show', compact('unit'));
    }

    public function edit(Unit $unit)
    {
        $houses = House::all();
        return view('units.edit', compact('unit', 'houses'));
    }

    public function update(Request $request, Unit $unit)
    {
        $request->validate([
            'house_id' => 'required|exists:houses,id',
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:apartment,commercial,house',
            'base_rent_cost' => 'required|numeric|min:0',
            'status' => 'required|string|in:vacant,occupied',
        ]);

        $unit->update([
            'house_id' => $request->house_id,
            'name' => $request->name,
            'type' => $request->type,
            'base_rent_cost' => $request->base_rent_cost,
            'status' => $request->status,
        ]);

        return redirect()->route('units.show', $unit)->with('success', 'Unidad actualizada con éxito.');
    }
}
