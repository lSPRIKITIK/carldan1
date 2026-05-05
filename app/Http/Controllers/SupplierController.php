<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suppliers = \App\Models\Supplier::all();
        return view('suppliers.index', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'supplier_name' => 'required|string',
            'supplier_contact' => 'required|string',
            'supplier_street' => 'required|string',
            'supplier_city' => 'required|string',
        ]);

        \App\Models\Supplier::create($data);
        return redirect()->route('suppliers.index')->with('success', 'Supplier added to directory!');
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function show(\App\Models\Supplier $supplier)
    {
        return view('suppliers.show', compact('supplier'));
    }

    public function edit(\App\Models\Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, \App\Models\Supplier $supplier)
    {
        $data = $request->validate([
            'supplier_name' => 'required|string',
            'supplier_contact' => 'required|string',
            'supplier_street' => 'required|string',
            'supplier_city' => 'required|string',
        ]);

        $supplier->update($data);
        return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully!');
    }

    public function destroy(\App\Models\Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully!');
    }
}
