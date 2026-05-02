<?php

namespace App\Http\Controllers;


use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $products = \App\Models\Product::when($search, function ($query, $search) {
                return $query->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('type', 'LIKE', "%{$search}%");
            })
            ->paginate(10)
            ->withQueryString();

        return view('products.index', compact('products'));
    }

    public function create()
    {
        $materials = \App\Models\Material::all();
        return view('products.create', compact('materials'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            
            'materials' => 'required|array', 
            'materials.*.material_id' => 'required|exists:materials,id',
            'materials.*.required_quantity' => 'required|integer|min:1',
        ]);

        
        $product = Product::create($request->only(['name', 'type', 'price']));

        
        $syncData = [];
        foreach ($request->materials as $mat) {
            $syncData[$mat['material_id']] = ['required_quantity' => $mat['required_quantity']];
        }

        
        $product->materials()->sync($syncData);

        return redirect()->route('products.index')->with('success', 'Product and Bill of Materials created!');
    }
    public function edit(Product $product)
    {
        // We need all materials for the dropdown, AND we need to load the product's current recipe
        $materials = \App\Models\Material::all();
        $product->load('materials');
        
        return view('products.edit', compact('product', 'materials'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'materials' => 'required|array',
            'materials.*.material_id' => 'required|exists:materials,id',
            'materials.*.required_quantity' => 'required|integer|min:1',
        ]);

        // 1. Update basic info
        $product->update($request->only(['name', 'type', 'price']));

        // 2. Format the materials array for Laravel's sync() method
        $syncData = [];
        foreach ($request->materials as $mat) {
            $syncData[$mat['material_id']] = ['required_quantity' => $mat['required_quantity']];
        }

        // 3. Sync wipes the old recipe and replaces it with the newly edited one
        $product->materials()->sync($syncData);

        return redirect()->route('products.index')->with('success', 'Product recipe updated successfully!');
    }

    public function destroy(Product $product)
    {
        // Safety check: Don't delete products that are already in someone's order
        if ($product->orders()->exists()) {
            return redirect()->route('products.index')->with('error', 'Cannot delete this product because it is linked to an existing order.');
        }

        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }
    
}