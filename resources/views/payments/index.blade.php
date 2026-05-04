@extends('layouts.app')

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <h2 class="text-2xl font-bold text-gray-800">Payments Management</h2>
        
        <div class="flex items-center gap-4 w-full md:w-auto">
            <!-- Search Form -->
            <form action="{{ route('payments.index') }}" method="GET" class="flex items-center gap-2">
                <div class="relative w-64">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Search Order ID or Client" 
                           class="border p-2 pr-10 rounded w-full shadow-sm focus:ring-2 focus:ring-blue-300 outline-none">
                    
                    @if(request('search'))
                        <a href="{{ route('payments.index') }}" class="absolute right-3 top-2.5 text-gray-400 hover:text-red-500 transition" title="Clear Search">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @endif
                </div>
                <button type="submit" class="bg-gray-800 text-white px-5 py-2 rounded hover:bg-gray-900 transition font-bold shadow-sm">
                    Search
                </button>
            </form>
        </div>
    </div>

    <div class="bg-white rounded border overflow-x-auto shadow-sm">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-100 text-gray-700 font-bold uppercase text-xs tracking-wider">
                <tr>
                    <th class="p-4 border-b">Order ID</th>
                    <th class="p-4 border-b">Client Name</th>
                    <th class="p-4 border-b">Products</th>
                    <th class="p-4 border-b text-right">Total Amount</th>
                    <th class="p-4 border-b text-right">Downpayment (50%)</th>
                    <th class="p-4 border-b text-right">Remaining Balance</th>
                    <th class="p-4 border-b text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($orders as $order)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-4 text-gray-500 font-mono font-semibold">
                            #{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}
                        </td>
                        <td class="p-4 text-gray-900 font-semibold">
                            {{ $order->client->first_name ?? '' }} {{ $order->client->last_name ?? '' }}
                        </td>
                        <td class="p-4 text-gray-600">
                            @foreach($order->products as $product)
                                <div class="text-xs">{{ $product->name }} <span class="text-gray-400">(x{{ $product->pivot->quantity }})</span></div>
                            @endforeach
                        </td>
                        <td class="p-4 text-right font-bold text-gray-800">
                            ₱{{ number_format($order->total_amount, 2) }}
                        </td>
                        <td class="p-4 text-right font-mono text-gray-600">
                            ₱{{ number_format($order->downpayment, 2) }}
                        </td>
                        <td class="p-4 text-right font-bold">
                            @if($order->remaining_balance <= 0)
                                <span class="text-green-600 bg-green-50 px-2 py-1 rounded">Paid</span>
                            @else
                                <span class="text-orange-500">₱{{ number_format($order->remaining_balance, 2) }}</span>
                            @endif
                        </td>
                        <td class="p-4 text-center">
                            <div class="flex justify-center gap-4 items-center">
                                <a href="{{ route('payments.show', $order->id) }}" class="text-blue-500 hover:text-blue-700 font-bold text-xs uppercase tracking-tight">
                                    View
                                </a>
                                
                                @if($order->remaining_balance > 0)
                                    <a href="{{ route('payments.create', ['order_id' => $order->id]) }}" class="text-green-600 hover:text-green-800 font-bold text-xs uppercase tracking-tight">
                                        Pay Balance
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-10 text-center text-gray-500 italic">
                            No payment records found matching your search.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Links -->
    <div class="mt-6">
        {{ $orders->links() }}
    </div>
@endsection