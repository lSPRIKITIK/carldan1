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
        $request->validate([
            'supplier_name' => 'required|string',
            'supplier_contact' => 'required',
            'supplier_address' => 'required',
        ]);

        \App\Models\Supplier::create($request->all());
        return back()->with('success', 'Supplier added to directory!');
    }
}
