@extends('layouts.app')

@section('content')
    <h2 class="text-2xl font-bold mb-6">Edit Order: #{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</h2>

    <form action="{{ route('orders.update', $order->id) }}" method="POST" class="max-w-3xl">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block font-bold mb-1">Update Client</label>
                <select name="client_id" required class="w-full border p-2 rounded bg-white">
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ $order->client_id == $client->id ? 'selected' : '' }}>
                            {{ $client->first_name }} {{ $client->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block font-bold mb-1">Update Handled By</label>
                <select name="employee_id" required class="w-full border p-2 rounded bg-white">
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" {{ $order->employee_id == $employee->id ? 'selected' : '' }}>
                            {{ $employee->first_name }} {{ $employee->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block font-bold mb-1">Order Date</label>
                <input type="date" name="order_date" value="{{ \Carbon\Carbon::parse($order->order_date)->format('Y-m-d') }}" required class="w-full border p-2 rounded">
            </div>
            <div>
                <label class="block font-bold mb-1">Target Delivery Date</label>
                <input type="date" name="delivery_date" value="{{ $order->delivery_date ? \Carbon\Carbon::parse($order->delivery_date)->format('Y-m-d') : '' }}" class="w-full border p-2 rounded">
            </div>
        </div>

        <!-- The Locked Shopping Cart Section -->
        <div class="mb-6 border p-6 rounded bg-gray-100">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-lg text-gray-800">🔒 Locked Order Items</h3>
                <span class="text-xs font-bold bg-gray-300 text-gray-700 px-2 py-1 rounded">Read Only</span>
            </div>
            
            <p class="text-sm text-gray-600 mb-4">
                To protect production history and inventory integrity, products cannot be changed once an order is placed. 
                If items need to be added or removed, please cancel this order and create a new one.
            </p>

            <table class="w-full text-sm text-left bg-white border">
                <thead class="bg-gray-200 text-gray-700">
                    <tr>
                        <th class="p-2 border-b">Product Name</th>
                        <th class="p-2 border-b text-center">Quantity Ordered</th>
                        <th class="p-2 border-b text-right">Historical Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->products as $item)
                        <tr>
                            <td class="p-2 border-b font-bold text-gray-800">{{ $item->name }}</td>
                            <td class="p-2 border-b text-center">{{ $item->pivot->quantity }} units</td>
                            <td class="p-2 border-b text-right text-gray-600">PHP {{ number_format($item->pivot->price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="bg-blue-600 text-white font-bold px-6 py-3 rounded hover:bg-blue-700 transition">Save Order Details</button>
            <a href="{{ route('orders.index') }}" class="flex items-center text-gray-600 font-bold px-6 py-3 rounded hover:bg-gray-200 transition">Cancel</a>
        </div>
    </form>
@endsection