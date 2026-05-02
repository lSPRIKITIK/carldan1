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
            // Record which supplier provided this batch
            $stock->update(['supplier_id' => $request->supplier_id]);
        }

        return back()->with('success', 'Stock updated successfully!');
    }
}
