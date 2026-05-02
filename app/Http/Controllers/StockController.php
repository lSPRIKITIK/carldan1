<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StockController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'material_id' => 'required|exists:materials,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'amount' => 'required|integer|min:1',
        ]);

        $stock = \App\Models\Stock::where('material_id', $request->material_id)->latest()->first();

        if ($stock) {
            $stock->increment('quantity', $request->amount);
            $stock->increment('stock_in', $request->amount);
            $stock->update(['supplier_id' => $request->supplier_id]);
        } else {
            \App\Models\Stock::create([
                'material_id' => $request->material_id,
                'supplier_id' => $request->supplier_id,
                'stock_in' => $request->amount,
                'stock_out' => 0,
                'quantity' => $request->amount,
            ]);
        }

        return redirect()->route('materials.index')->with('success', 'Stock updated successfully!');
    }
    
    public function create(Request $request)
    {
        if (!$request->has('material_id')) {
            return redirect()->route('materials.index')->with('error', 'Please select a material to restock from the table.');
        }

        $material = \App\Models\Material::findOrFail($request->material_id);
        $suppliers = \App\Models\Supplier::all();

        return view('stocks.create', compact('material', 'suppliers'));
    }

    
}
