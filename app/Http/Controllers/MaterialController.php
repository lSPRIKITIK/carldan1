<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function index()
    {
        $materials = \App\Models\Material::with('stocks')->latest()->get();
        
        return view('materials.index', compact('materials'));
    }
    public function create()
    {
        return view('materials.create');
    }

    public function store(Request $request)
    {
        
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
        ]);

        \App\Models\Material::create([
            'name' => $request->name,
            'type' => $request->type
        ]);

        return redirect()->route('materials.index')->with('success', 'Material created successfully!');
    }

    public function destroy(Material $material)
    {
        $material->delete();
        return redirect()->route('materials.index')->with('success', 'Material deleted successfully!');
    }
    public function edit(Material $material)
    {
        return view('materials.edit', compact('material'));
    }

    public function update(Request $request, Material $material)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'unit_cost' => 'required|numeric|min:0',
        ]);

        $material->update($request->all());
        return redirect()->route('materials.index')->with('success', 'Material updated successfully!');
    }
}