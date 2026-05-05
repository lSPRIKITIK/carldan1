@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-gray-800">
            Order #{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }} Command Center
        </h2>
        <a href="{{ route('orders.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded transition">
            Back to Orders
        </a>
    </div>

    <!-- Top Section: Order Details -->
    <div class="grid grid-cols-2 gap-6 mb-8">
        <!-- Combined Order Details -->
        <div class="bg-white border p-6 rounded shadow-sm col-span-2">
            <h3 class="text-lg font-bold text-blue-600 border-b pb-2 mb-4">Order Details</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <h4 class="text-sm font-bold text-gray-500 uppercase mb-2">Client Information</h4>
                    <p><strong>Name:</strong> {{ $order->client->first_name }} {{ $order->client->last_name }}</p>
                    <p><strong>Contact:</strong> {{ $order->client->contact_number }}</p>
                    <p><strong>Address:</strong> {{ $order->client->street }}, {{ $order->client->city }}</p>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-gray-500 uppercase mb-2">Additional Information</h4>
                    <p><strong>Order Date:</strong> {{ \Carbon\Carbon::parse($order->order_date)->format('M d, Y') }}</p>
                    <p><strong>Target Delivery:</strong> {{ $order->delivery_date ? \Carbon\Carbon::parse($order->delivery_date)->format('M d, Y') : 'Not Set' }}</p>
                    <p><strong>Handled By:</strong> {{ $order->employee->first_name }} {{ $order->employee->last_name }}</p>
                </div>
            </div>
        </div>
        
        <!-- Financial Summary Card -->
        <div class="bg-white border p-6 rounded shadow-sm col-span-2">
            <h3 class="text-lg font-bold text-green-600 border-b pb-2 mb-4">Payment Summary</h3>
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-600">Total Order Value</p>
                    <p class="text-2xl font-bold text-gray-800">₱{{ number_format($order->products->sum(fn($p) => $p->pivot->price * $p->pivot->quantity), 2) }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-600">Balance Status</p>
                    @php
                        $totalPaid = $order->payments->sum('amount');
                        $totalCost = $order->products->sum(fn($p) => $p->pivot->price * $p->pivot->quantity);
                    @endphp
                    
                    @if($totalPaid >= $totalCost)
                        <span class="bg-green-100 text-green-700 px-4 py-1 rounded-full font-bold">FULLY PAID</span>
                    @elseif($totalPaid > 0)
                        <span class="bg-yellow-100 text-yellow-700 px-4 py-1 rounded-full font-bold">PARTIAL: PHP {{ number_format($totalPaid, 2) }}</span>
                    @else
                        <span class="bg-red-100 text-red-700 px-4 py-1 rounded-full font-bold">UNPAID</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Section: The Production Tracker -->
    <div class="bg-gray-800 text-white p-4 rounded-t-lg">
        <h3 class="text-xl font-bold"> Workshop Production Tracker</h3>
    </div>
    
    <div class="bg-white border border-t-0 p-6 rounded-b-lg shadow-sm">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-100 text-gray-700 font-bold">
                <tr>
                    <th class="p-3 border-b">Product Name</th>
                    <th class="p-3 border-b text-center">Status</th>
                    <th class="p-3 border-b text-center">Dates Logged</th>
                    <th class="p-3 border-b">Workshop Notes</th>
                    <th class="p-3 border-b">Update Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($order->productions as $production)
                    <tr class="hover:bg-gray-50 transition border-b">
                        <td class="p-3 font-bold text-gray-900">{{ $production->product->name }}</td>
                        
                        <!-- Status Badge -->
                        <td class="p-3 text-center">
                            @if($production->prod_status === 'Pending')
                                <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full font-bold text-xs">Pending</span>
                            @elseif($production->prod_status === 'In Production')
                                <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full font-bold text-xs">In Production</span>
                            @else
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full font-bold text-xs">Completed</span>
                            @endif
                        </td>

                        <!-- Timestamps -->
                        <td class="p-3 text-center text-xs text-gray-600">
                            <div><strong>Started:</strong> {{ $production->prod_start_date ? \Carbon\Carbon::parse($production->prod_start_date)->format('M d') : '--' }}</div>
                            <div><strong>Finished:</strong> {{ $production->prod_finished_date ? \Carbon\Carbon::parse($production->prod_finished_date)->format('M d') : '--' }}</div>
                        </td>

                        <!-- Notes & Update Form -->
                        <form action="{{ route('productions.update', $production->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <td class="p-3">
                                <input type="text" name="prod_note" value="{{ $production->prod_note }}" placeholder="Add a note..." class="w-full border p-1 text-sm rounded">
                            </td>
                            
                            <td class="p-3 flex gap-2">
                                <select name="prod_status" class="border p-1 text-sm rounded cursor-pointer">
                                    <option value="Pending" {{ $production->prod_status === 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="In Production" {{ $production->prod_status === 'In Production' ? 'selected' : '' }}>In Production</option>
                                    <option value="Completed" {{ $production->prod_status === 'Completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm font-bold transition">Save</button>
                            </td>
                        </form>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-4 text-center text-gray-600">No production records found for this order.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection