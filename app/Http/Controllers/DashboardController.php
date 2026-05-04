<?php

namespace App\Http\Controllers;


class DashboardController extends Controller
{
    public function index()
    {
        $allOrders = \App\Models\Order::with('products')->get();
        $totalRevenue = 0;
        
        foreach($allOrders as $order) {
            $totalRevenue += $order->products->sum(function($product) {
                return $product->pivot->quantity * $product->pivot->price;
            });
        }

        $totalCollected = \App\Models\Payment::sum('amount');
        $totalPending = max(0, $totalRevenue - $totalCollected);

        $recentOrders = \App\Models\Order::with(['client', 'products', 'payments'])
                            ->latest()
                            ->paginate(10);

        foreach ($recentOrders as $order) {
            $order->total = $order->products->sum(function($product) {
                return $product->pivot->quantity * $product->pivot->price;
            });
            $order->paid = $order->payments->sum('amount');
            $order->balance = $order->total - $order->paid;
            $order->status = $order->balance <= 0 ? 'Paid' : 'Pending';
        }

        return view('dashboard.index', compact(
            'totalRevenue', 
            'totalCollected', 
            'totalPending', 
            'recentOrders'
        ));
    }
}