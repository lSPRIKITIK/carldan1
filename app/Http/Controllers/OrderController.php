<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Client;
use App\Models\Employee;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = \App\Models\Order::with(['client', 'employee', 'products'])->get();
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $clients = \App\Models\Client::all();
        $employees = \App\Models\Employee::all();
        $products = \App\Models\Product::with('materials')->get();
        return view('orders.create', compact('clients', 'employees', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'employee_id' => 'required|exists:employees,id',
            'order_date' => 'required|date',
            'delivery_date' => 'nullable|date|after_or_equal:order_date',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request) {
            
            $order = Order::create([
                'client_id' => $request->client_id,
                'employee_id' => $request->employee_id,
                'order_date' => $request->order_date,
                'delivery_date' => $request->delivery_date,
                'status' => 'Pending',
            ]);

            foreach ($request->products as $item) {
                $product = Product::with('materials')->findOrFail($item['product_id']);
                $order->products()->attach($product->id, [
                    'quantity' => $item['quantity'],
                    'price' => $product->price 
                ]);
                foreach ($product->materials as $material) {
                    $totalNeeded = $material->pivot->required_quantity * $item['quantity'];
                    
                    $stock = Stock::where('material_id', $material->id)->latest()->first();
                    
                    if ($stock) {
                        $stock->decrement('quantity', $totalNeeded);
                        $stock->increment('stock_out', $totalNeeded);
                    }
                }
                \App\Models\Production::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'prod_status' => 'Pending',
                ]);
            }
            
        });
        
        return redirect()->route('orders.index')->with('success', 'Order created, inventory updated, and production tickets generated!');
    }
    public function edit(Order $order)
    {
        $order->load('products');
        $clients = \App\Models\Client::all();
        $employees = \App\Models\Employee::all();
        
        return view('orders.edit', compact('order', 'clients', 'employees'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'employee_id' => 'required|exists:employees,id',
            'order_date' => 'required|date',
            'delivery_date' => 'nullable|date',
        ]);

        $order->update($request->only(['client_id', 'employee_id', 'order_date', 'delivery_date']));

        return redirect()->route('orders.index')->with('success', 'Order details updated successfully!');
    }

    public function destroy(Order $order)
    {
        if ($order->payments()->exists()) {
            return redirect()->route('orders.index')->with('error', 'Cannot delete this order because payments have already been recorded with it.');
        }

        $order->products()->detach(); 
        $order->delete();
        
        return redirect()->route('orders.index')->with('success', 'Order cancelled and deleted successfully!');
    }
    public function show(Order $order)
    {
        $order->load(['client', 'employee', 'productions.product']);
        
        return view('orders.show', compact('order'));
    }
}