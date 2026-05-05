<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Order::with(['client', 'employee'])
            ->orderBy('order_date', 'desc');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('id', 'like', "%{$search}%")
                  ->orWhereHas('client', function($q) use ($search) {
                      $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                  });
        }

        $orders = $query->paginate(10)->withQueryString();

        return view('orders.index', compact('orders'));
    }
    public function create()
    {
        // Fetch data for all the dropdowns
        $clients = \App\Models\Client::all();
        $products = \App\Models\Product::all();
        $employees = \App\Models\Employee::all(); 

        return view('orders.create', compact('clients', 'products', 'employees'));
    }

    public function store(Request $request)
    {
        // 1. Base Validation
        $rules = [
            'client_id'        => 'required',
            'employee_id'      => 'required|exists:employees,id',
            'order_status'     => 'required|string',
            'order_date'       => 'required|date',
            'delivery_date'    => 'required|date|after_or_equal:order_date',
            
            'product_id'       => 'required|array',
            'product_id.*'     => 'required|exists:products,id',
            'quantity'         => 'required|array',
            'quantity.*'       => 'required|numeric|min:1',

            // Payment Validation
            'payment_method'   => 'required|string',
            'reference_number' => 'nullable|string',
        ];

        // Conditional Client Validation
        if ($request->client_id === 'new') {
            $rules['client_fn']             = 'required|string|max:255';
            $rules['client_mn']             = 'nullable|string|max:255';
            $rules['client_ln']             = 'required|string|max:255';
            $rules['client_contact_number'] = 'required|string|max:255';
            $rules['client_street']         = 'required|string|max:255';
            $rules['client_city']           = 'required|string|max:255';
        }

        $request->validate($rules);

        // 2. INVENTORY CAPACITY CHECK
        $dbProducts = \App\Models\Product::with('materials.stocks')->whereIn('id', $request->product_id)->get()->keyBy('id');

        foreach ($request->product_id as $index => $prodId) {
            $requestedQty = $request->quantity[$index];
            $product = $dbProducts[$prodId];

            $maxBuildable = PHP_INT_MAX; 

            foreach ($product->materials as $material) {
                $totalMaterialStock = $material->stocks->sum('quantity'); 
                $requiredPerProduct = $material->pivot->required_quantity; 
                
                // Prevent division by zero if a recipe is broken
                if($requiredPerProduct > 0) {
                    $possibleBuilds = floor($totalMaterialStock / $requiredPerProduct);
                    if ($possibleBuilds < $maxBuildable) {
                        $maxBuildable = $possibleBuilds;
                    }
                }
            }

            if ($requestedQty > $maxBuildable) {
                return back()
                    ->withInput() 
                    ->withErrors([
                        'quantity' => "Capacity Warning: You requested {$requestedQty} units of '{$product->name}', but you only have enough raw materials to build {$maxBuildable}."
                    ]);
            }
        }

        // 3. Handle Client Creation
        if ($request->client_id === 'new') {
            $client = \App\Models\Client::create([
                'first_name'     => $request->client_fn,
                'middle_name'    => $request->client_mn,
                'last_name'      => $request->client_ln,
                'contact_number' => $request->client_contact_number,
                'street'         => $request->client_street, 
                'city'           => $request->client_city,   
            ]);
            $finalClientId = $client->id; 
        } else {
            $finalClientId = $request->client_id;
        }

        // 4. Create the Order + Attach products + Deduct material stocks atomically
        $downpaymentAmount = 0;

        $order = DB::transaction(function() use ($request, $dbProducts, $finalClientId, &$downpaymentAmount) {
            // Create Order
            $order = \App\Models\Order::create([
                'client_id'     => $finalClientId,
                'employee_id'   => $request->employee_id,
                'status'        => $request->order_status,
                'order_date'    => $request->order_date,
                'delivery_date' => $request->delivery_date,
            ]);

            // Attach Products AND Calculate Total
            $productsToAttach = [];
            $totalOrderPrice = 0; 

            foreach ($request->product_id as $index => $prodId) {
                $qty = $request->quantity[$index];
                $price = $dbProducts[$prodId]->price;
                
                $productsToAttach[$prodId] = [
                    'quantity' => $qty,
                    'price'    => $price, 
                ];

                $totalOrderPrice += ($price * $qty);
            }
            
            $order->products()->attach($productsToAttach);

            // Create production entries for each product in this order
            $productionsToCreate = [];
            foreach ($request->product_id as $prodId) {
                $productionsToCreate[] = [
                    'product_id' => $prodId,
                    'prod_status' => 'Pending',
                ];
            }
            if (!empty($productionsToCreate)) {
                $order->productions()->createMany($productionsToCreate);
            }

            // Stock deductions moved to DB triggers (MySQL). Laravel deduction removed.

            // Record the 50% Downpayment
            $downpaymentAmount = $totalOrderPrice * 0.50;

            \App\Models\Payment::create([
                'order_id'         => $order->id,
                'employee_id'      => $request->employee_id,
                'payment_method'   => $request->payment_method,
                'payment_date'     => now(), 
                'amount'           => $downpaymentAmount,
                'reference_number' => $request->reference_number, 
            ]);

            return $order;
        });

        return redirect()->route('orders.index')->with('success', 'Order created with ₱' . number_format($downpaymentAmount, 2) . ' downpayment recorded!');
    }

    /**
     * Display the specified order and its production details.
     *
     * @param  string  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {

        $order = \App\Models\Order::with([
            'client', 'employee', 'products', 'payments', 'productions.product'
        ])->findOrFail($id);

        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified order.
     *
     * @param  string  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $order = \App\Models\Order::with(['products', 'client', 'employee'])->findOrFail($id);

        $clients = \App\Models\Client::all();
        $products = \App\Models\Product::all();
        $employees = \App\Models\Employee::all();

        $product_quantities = $order->products->mapWithKeys(function ($product) {
            return [$product->id => $product->pivot->quantity];
        });

        return view('orders.edit', compact('order', 'clients', 'products', 'employees', 'product_quantities'));
    }

    /**
     * Update the specified order in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'client_id'     => 'required|exists:clients,id',
            'employee_id'   => 'required|exists:employees,id',
            'order_status'  => 'required|string',
            'order_date'    => 'required|date',
            'delivery_date' => 'nullable|date|after_or_equal:order_date',
        ]);

        $order = \App\Models\Order::findOrFail($id);
        // Safely updates the status. If changed to 'Confirmed', the MySQL trigger will handle stock deduction.
        $order->update([
            'client_id' => $request->client_id,
            'employee_id' => $request->employee_id,
            'status' => $request->order_status,
            'order_date' => $request->order_date,
            'delivery_date' => $request->delivery_date,
        ]);

        return redirect()->route('orders.index')->with('success', 'Order updated successfully!');
    }

    /**
     * Remove the specified order from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $order = \App\Models\Order::with(['payments', 'productions'])->findOrFail($id);

        // Detach products pivot to clean up order_product
        $order->products()->detach();

        // Delete related payments (if any)
        $order->payments()->delete();

        // Productions are cascade deleted by migration but ensure removal
        $order->productions()->delete();

        $order->delete();

        return redirect()->route('orders.index')->with('success', 'Order deleted successfully!');
    }
}