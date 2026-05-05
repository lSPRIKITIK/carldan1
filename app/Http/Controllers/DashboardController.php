<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the dashboard relying heavily on MySQL Views.
     */
    public function index()
    {
        // Query the reporting views directly as dictated by system architecture
        $monthlyRevenue = DB::table('dashboard_revenue')->get();
        $totalRevenue = DB::table('dashboard_total_revenue')->first();

        $recentOrders = \App\Models\Order::with('client')
            ->orderBy('order_date', 'desc')
            ->take(5)
            ->get();

        return view('dashboard', compact('monthlyRevenue', 'totalRevenue', 'recentOrders'));
    }
}