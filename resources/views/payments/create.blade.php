@extends('layouts.app')

@section('content')
    <h2 class="text-2xl font-bold mb-6">Record New Payment</h2>

    <form action="{{ route('payments.store') }}" method="POST" class="max-w-2xl bg-white p-6 rounded border">
        @csrf

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block font-bold mb-1 text-gray-700">Link to Order</label>
                <select name="order_id" required class="w-full border p-2 rounded focus:ring focus:ring-blue-200">
                    <option value="" disabled selected>Select an Order...</option>
                    @foreach($orders as $order)
                        <option value="{{ $order->id }}">PO-{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }} ({{ $order->client->first_name }} {{ $order->client->last_name }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block font-bold mb-1 text-gray-700">Processed By</label>
                <select name="employee_id" required class="w-full border p-2 rounded focus:ring focus:ring-blue-200">
                    <option value="" disabled selected>Select Cashier/Employee...</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}">{{ $employee->first_name }} {{ $employee->last_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block font-bold mb-1 text-gray-700">Payment Method</label>
                <select name="payment_method" required class="w-full border p-2 rounded focus:ring focus:ring-blue-200">
                    <option value="Cash">Cash</option>
                    <option value="GCash">GCash</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                    <option value="Cheque">Cheque</option>
                </select>
            </div>
            <div>
                <label class="block font-bold mb-1 text-gray-700">Payment Date</label>
                <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" required class="w-full border p-2 rounded focus:ring focus:ring-blue-200">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block font-bold mb-1 text-gray-700">Amount Paid (PHP)</label>
                <input type="number" name="amount" step="0.01" min="0" required placeholder="0.00" class="w-full border p-2 rounded focus:ring focus:ring-blue-200">
            </div>
            <div>
                <label class="block font-bold mb-1 text-gray-700">Reference Number (Optional)</label>
                <input type="text" name="reference_number" placeholder="e.g., GCash Ref No." class="w-full border p-2 rounded focus:ring focus:ring-blue-200">
            </div>
        </div>

        <div class="flex gap-4 mt-6">
            <button type="submit" class="bg-blue-600 text-white font-bold px-6 py-2 rounded hover:bg-blue-700 transition">Save Payment</button>
            <a href="{{ route('payments.index') }}" class="flex items-center text-gray-600 font-bold px-6 py-2 rounded hover:bg-gray-100 transition">Cancel</a>
        </div>
    </form>
@endsection