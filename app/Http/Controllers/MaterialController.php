<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $materials = \App\Models\Material::with('stocks')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('type', 'LIKE', "%{$search}%");
            })
            ->paginate(10)
            ->withQueryString();

        return view('materials.index', compact('materials', 'search'));
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
            'unit_cost' => 'required|numeric|min:0',
        ]);

        
        Material::create([
            'name' => $request->name,
            'type' => $request->type,
            'unit_cost' => $request->unit_cost,
        ]);

        
        return redirect()->route('materials.index')->with('success', 'New material added successfully!');
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