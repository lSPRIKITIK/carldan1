<?php

namespace App\Http\Controllers;

use App\Models\Production;
use Illuminate\Http\Request;

class ProductionController extends Controller
{
    public function update(Request $request, Production $production)
    {
        $request->validate([
            'prod_status' => 'required|in:Pending,In Production,Completed',
            'prod_note' => 'nullable|string'
        ]);

        $data = [
            'prod_status' => $request->prod_status,
            'prod_note' => $request->prod_note,
        ];

        if ($request->prod_status === 'In Production' && !$production->prod_start_date) {
            $data['prod_start_date'] = now();
        } elseif ($request->prod_status === 'Completed' && !$production->prod_finished_date) {
            $data['prod_finished_date'] = now();
            if (!$production->prod_start_date) $data['prod_start_date'] = now();
        }

        $production->update($data);

        // --- NEW REFINEMENT LOGIC ---
        $order = $production->order;
        $allProductions = $order->productions;

        if ($allProductions->every('prod_status', 'Completed')) {
            $order->update(['status' => 'Completed']);
        } elseif ($allProductions->contains('prod_status', 'In Production')) {
            $order->update(['status' => 'In Production']);
        }
        // ----------------------------

        return back()->with('success', 'Production updated and Order status synced!');
    }
}