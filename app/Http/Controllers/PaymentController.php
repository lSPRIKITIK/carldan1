<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Order;
use App\Models\Employee;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request) 
    {
        $query = \App\Models\Order::with(['client', 'products', 'payments'])->latest();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            
            $query->where('id', 'like', "%{$search}%")
                ->orWhereHas('client', function($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                });
        }

        $orders = $query->paginate(10);
        
        return view('payments.index', compact('orders'));
    }

    public function create(Request $request) 
    {
        $orderId = $request->query('order_id');

        
        $order = \App\Models\Order::with(['client', 'products', 'payments'])->findOrFail($orderId);
        $employees = \App\Models\Employee::all();

        
        $totalAmount = $order->products->sum(function($product) {
            return $product->pivot->quantity * $product->pivot->price;
        });
        
        
        $amountPaid = $order->payments->sum('amount');
        $remainingBalance = max(0, $totalAmount - $amountPaid);

        return view('payments.create', compact('order', 'employees', 'remainingBalance'));
    }

    public function store(Request $request) 
    {
        
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'employee_id' => 'required|exists:employees,id',
            'payment_method' => 'required|string',
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:1',
            'reference_number' => 'nullable|string|max:255', 
        ]);

        
        \App\Models\Payment::create([
            'order_id' => $request->order_id,
            'employee_id' => $request->employee_id,
            'payment_method' => $request->payment_method,
            'payment_date' => $request->payment_date,
            'amount' => $request->amount,
            'reference_number' => $request->reference_number, 
        ]);

        return redirect()->route('payments.index')->with('success', 'Payment processed successfully!');
    }
    public function edit(Payment $payment)
    {
        $orders = Order::with('client')->get();
        $employees = Employee::all();
        return view('payments.edit', compact('payment', 'orders', 'employees'));
    }

    public function update(Request $request, Payment $payment)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'employee_id' => 'required|exists:employees,id',
            'payment_method' => 'required|string',
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'reference_number' => 'nullable|string',
        ]);

        $payment->update($request->all());
        return redirect()->route('payments.index')->with('success', 'Payment updated successfully!');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('payments.index')->with('success', 'Payment deleted successfully!');
    }
    public function show($id)
    {
        $order = \App\Models\Order::with(['client', 'products', 'payments.employee'])->findOrFail($id);

        $order->total_amount = $order->products->sum(function($product) {
            return $product->pivot->quantity * $product->pivot->price;
        });
        
        $order->amount_paid = $order->payments->sum('amount');
        $order->remaining_balance = $order->total_amount - $order->amount_paid;

        return view('payments.show', compact('order'));
    }
}