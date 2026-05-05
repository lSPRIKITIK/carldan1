<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Supplier;
use App\Models\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /**
     * Store a newly created stock record in storage.
     */
    public function store(Request $request)
    {
        
        $rules = [
            'material_id' => 'required|exists:materials,id',
            'amount'      => 'required|integer|min:1',
            'unit_cost'   => 'required|numeric|min:0',
            'supplier_id' => 'required' 
        ];

        if ($request->supplier_id === 'new') {
            $rules['supplier_name']    = 'required|string|max:255';
            $rules['supplier_contact'] = 'required|string|max:255';
            $rules['supplier_street']  = 'required|string|max:255';
            $rules['supplier_city']    = 'required|string|max:255';
        }

        $request->validate($rules);

        if ($request->supplier_id === 'new') {
            $supplier = Supplier::firstOrCreate(
                ['supplier_name' => $request->supplier_name],
                [
                    'supplier_contact' => $request->supplier_contact,
                    'supplier_street'  => $request->supplier_street,
                    'supplier_city'    => $request->supplier_city,
                ]
            );
            $finalSupplierId = $supplier->id;
        } else {
            $finalSupplierId = $request->supplier_id;
        }

        $stock = Stock::create([
            'material_id' => $request->material_id,
            'supplier_id' => $finalSupplierId,
            'stock_in'    => $request->amount,
            'quantity'    => $request->amount,
            'unit_cost'   => $request->unit_cost, 
        ]);

        // Record stock IN movement
        \App\Models\StockMovement::create([
            'stock_id'     => $stock->id,
            'material_id'  => $stock->material_id,
            'movement_type'=> 'in',
            'quantity'     => $stock->stock_in,
        ]);

        $material = Material::findOrFail($request->material_id);
        $material->price = $request->unit_cost;
        $material->save();

        return redirect()->route('materials.index')
            ->with('success', 'Material restocked and unit cost updated successfully!');
    }

    /**
     * Show the form for creating a new stock record.
     */
    public function create(Request $request)
    {
        if (!$request->has('material_id')) {
            return redirect()->route('materials.index')
                ->with('error', 'Please select a material to restock from the table.');
        }

        $material = Material::findOrFail($request->material_id);
        $suppliers = Supplier::all();

        return view('stocks.create', compact('material', 'suppliers'));
    }
}