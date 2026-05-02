@extends('layouts.app')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <h2 class="text-2xl font-bold text-gray-800">Orders</h2>
        <div class="flex items-center gap-4 w-full md:w-auto">
            <!-- Search Form -->
            <form action="{{ route('orders.index') }}" method="GET" class="flex items-center gap-2">
                <div class="relative w-64">
                    <input type="text" name="search" value="{{ request('search') }}" 
                        placeholder="Search Name or ID" 
                        class="border p-2 pr-10 rounded w-full shadow-sm focus:ring-2 focus:ring-blue-300 outline-none">
                    @if(request('search'))
                        <a href="{{ route('orders.index') }}" class="absolute right-3 top-2.5 text-gray-400 hover:text-red-500 transition" title="Clear Search">
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
            <a href="{{ route('orders.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition shadow-sm whitespace-nowrap">
                + Create New Order
            </a>
        </div>
</div>
    <div class="bg-white rounded border overflow-x-auto shadow-sm">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-100 text-gray-700 font-bold uppercase text-xs tracking-wider">
                <tr>
                    <th class="p-4 border-b">Order ID</th>
                    <th class="p-4 border-b">Client Name</th>
                    <th class="p-4 border-b">Products Ordered</th>
                    <th class="p-4 border-b">Order Date</th>
                    <th class="p-4 border-b">Status</th>
                    <th class="p-4 border-b">Handled By</th>
                    <th class="p-4 border-b text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($orders as $order)
                    <tr class="hover:bg-gray-50 transition">
                        
                        <td class="p-4 font-bold text-gray-500">
                            #{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}
                        </td>
                        
                        <td class="p-4 text-gray-900 font-semibold">
                            {{ $order->client->first_name }} {{ $order->client->last_name }}
                        </td>

                        <td class="p-4 text-gray-600">
                            <span class="bg-gray-100 px-2 py-1 rounded text-xs">
                                {{ $order->products->count() }} {{ Str::plural('Item', $order->products->count()) }}
                            </span>
                        </td>

                        <td class="p-4 text-gray-600">
                            {{ \Carbon\Carbon::parse($order->order_date)->format('M d, Y') }}
                        </td>

                        <td class="p-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold shadow-sm
                                {{ $order->status === 'Pending' ? 'bg-red-100 text-red-700' : '' }}
                                {{ $order->status === 'In Production' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $order->status === 'Completed' ? 'bg-green-100 text-green-700' : '' }}">
                                {{ $order->status }}
                            </span>
                        </td>

                        <td class="p-4 text-gray-600">
                            {{ $order->employee->first_name }}
                        </td>

                        <td class="p-4 text-center">
                            <div class="flex justify-center gap-4">
                                <!-- View Action (Links to Show/Production Tracker) -->
                                <a href="{{ route('orders.show', $order->id) }}" class="text-green-600 hover:text-green-800 font-bold transition">
                                    View
                                </a>

                                <!-- Edit Action -->
                                <a href="{{ route('orders.edit', $order->id) }}" class="text-blue-500 hover:text-blue-700 font-bold transition">
                                    Edit
                                </a>
                                
                                <!-- Delete Action -->
                                <form action="{{ route('orders.destroy', $order->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to cancel and delete this entire order?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 font-bold bg-transparent border-none p-0 transition">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-10 text-center text-gray-500 italic">
                            No orders found. Create your first order to start production tracking!
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">
        {{ $orders->links() }}
    </div>
@endsection