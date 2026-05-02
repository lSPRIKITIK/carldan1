<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $orders = \App\Models\Order::with(['client', 'employee'])
            ->when($search, function ($query, $search) {
                return $query->where('id', 'LIKE', "%{$search}%")
                    ->orWhereHas('client', function ($q) use ($search) {
                        $q->where('first_name', 'LIKE', "%{$search}%")
                        ->orWhere('last_name', 'LIKE', "%{$search}%");
                    });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();
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
        $order = \App\Models\Order::create([
            'client_id'     => $request->client_id,
            'employee_id'   => $request->employee_id,
            'order_date'    => now(),
            'status'        => 'Pending',
        ]);

        $totalPrice = 0;
        foreach ($request->items as $item) {
            $subtotal = $item['quantity'] * $item['price'];
            $totalPrice += $subtotal;

            $order->products()->attach($item['product_id'], [
                'quantity' => $item['quantity'],
                'price'    => $item['price']
            ]);
        }

        \App\Models\Payment::create([
            'order_id'       => $order->id,
            'employee_id'    => $request->employee_id, 
            'payment_method' => $request->payment_method, 
            'payment_date'   => now(),
            'amount'         => $totalPrice / 2, 
            'reference_number' => $request->reference_number ?? 'DOWNPAYMENT',
        ]);

        return redirect()->route('orders.index')->with('success', 'Order created and 50% downpayment recorded!');
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