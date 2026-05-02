@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Orders Management</h2>
        <a href="{{ route('orders.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition shadow-sm">
            + Create New Order
        </a>
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
                            {{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}
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
@endsection