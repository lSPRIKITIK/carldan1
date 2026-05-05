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
            'name'          => 'required|string|max:255',
            'type'          => 'required|string|max:255',
            'price'         => 'required|numeric|min:0', 
            'material_id'   => 'required|array',
            'material_id.*' => 'required|exists:materials,id',
            'quantity'      => 'required|array',
            'quantity.*'    => 'required|numeric|min:1',
        ]);

        $totalCost = 0;

        $materials = \App\Models\Material::whereIn('id', $request->material_id)->get()->keyBy('id');

        foreach ($request->material_id as $index => $materialId) {
            $qty = $request->quantity[$index];
            
            if (isset($materials[$materialId])) {
                $totalCost += ($materials[$materialId]->price * $qty);
            }
        }

        if ($totalCost > $request->price) {
            return back()
                ->withInput() 
                ->withErrors([
                    'price' => 'Loss Warning: The selling price (₱' . number_format($request->price, 2) . 
                            ') is lower than the total cost of materials (₱' . number_format($totalCost, 2) . '). Please increase the selling price.'
                ]);
        }

        $product = \App\Models\Product::create([
            'name'  => $request->name,
            'type'  => $request->type,
            'price' => $request->price,
        ]);


        $materialsToAttach = [];
        foreach ($request->material_id as $index => $materialId) {
            $materialsToAttach[$materialId] = ['required_quantity' => $request->quantity[$index]];
        }
        
        $product->materials()->attach($materialsToAttach);

        return redirect()->route('products.index')->with('success', 'Product created successfully!');
    }
    public function edit(Product $product)
    {

        $materials = \App\Models\Material::all();
        $product->load('materials');
        
        return view('products.edit', compact('product', 'materials'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'type'          => 'required|string|max:255',
            'price'         => 'required|numeric|min:0', 
            'material_id'   => 'required|array',
            'material_id.*' => 'required|exists:materials,id',
            'quantity'      => 'required|array',
            'quantity.*'    => 'required|numeric|min:1',
        ]);

        $totalCost = 0;
        $materials = \App\Models\Material::whereIn('id', $request->material_id)->get()->keyBy('id');

        foreach ($request->material_id as $index => $materialId) {
            $qty = $request->quantity[$index];
            if (isset($materials[$materialId])) {
                $totalCost += ($materials[$materialId]->price * $qty);
            }
        }
        if ($totalCost > $request->price) {
            return back()
                ->withInput() 
                ->withErrors([
                    'price' => 'Loss Warning: The selling price (₱' . number_format($request->price, 2) . 
                            ') is lower than the total cost of materials (₱' . number_format($totalCost, 2) . '). Please increase the selling price.'
                ]);
        }

        $product = \App\Models\Product::findOrFail($id);
        $product->update([
            'name'  => $request->name,
            'type'  => $request->type,
            'price' => $request->price,
        ]);

        $materialsToSync = [];
        foreach ($request->material_id as $index => $materialId) {
            $materialsToSync[$materialId] = ['required_quantity' => $request->quantity[$index]];
        }

        $product->materials()->sync($materialsToSync);

        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        if ($product->orders()->exists()) {
            return redirect()->route('products.index')->with('error', 'Cannot delete this product because it is linked to an existing order.');
        }

        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }
    public function show($id)
    {
        $product = \App\Models\Product::with('materials.stocks')->findOrFail($id);

        $buildableQuantities = [];

        foreach ($product->materials as $material) {
            $totalStock = $material->stocks->sum('quantity');
            
            $material->total_stock = $totalStock;

            $requiredQty = $material->pivot->required_quantity;
            
            if ($requiredQty > 0) {
                $buildableQuantities[] = floor($totalStock / $requiredQty); 
            }
        }

        $maxBuildable = count($buildableQuantities) > 0 ? min($buildableQuantities) : 0;

        return view('products.show', compact('product', 'maxBuildable'));
    }
    
}