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
        $search = $request->input('search');

        $payments = \App\Models\Payment::with(['order.client'])
            ->when($search, function ($query, $search) {
                return $query->where('reference_number', 'LIKE', "%{$search}%")
                    ->orWhereHas('order.client', function ($q) use ($search) {
                        $q->where('first_name', 'LIKE', "%{$search}%")
                        ->orWhere('last_name', 'LIKE', "%{$search}%");
                    });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('payments.index', compact('payments'));
    }

    public function create(Request $request)
    {
        $order = \App\Models\Order::with('products')->findOrFail($request->order_id);
        
        $totalAmount = $order->products->sum(function($product) {
            return $product->pivot->quantity * $product->pivot->price;
        });

        $alreadyPaid = \App\Models\Payment::where('order_id', $order->id)->sum('amount');
        $remainingBalance = $totalAmount - $alreadyPaid;

        return view('payments.create', compact('order', 'remainingBalance'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'employee_id' => 'required|exists:employees,id',
            'payment_method' => 'required|string',
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'reference_number' => 'nullable|string',
        ]);

        Payment::create($request->all());

        return redirect()->route('payments.index')->with('success', 'Payment recorded successfully!');
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
}