@extends('layouts.app')

@section('content')
<h2 class="text-2xl font-bold mb-6">Final Payment Settlement</h2>

<div class="max-w-lg bg-white p-6 rounded border shadow-sm">
    <div class="mb-6 p-4 bg-blue-50 border-l-4 border-blue-500">
        <p class="text-sm text-blue-700 font-bold uppercase">Order Reference</p>
        <p class="text-xl font-mono">PO-{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</p>
        <p class="text-sm text-gray-600">Client: {{ $order->client->first_name }} {{ $order->client->last_name }}</p>
    </div>

    <form action="{{ route('payments.store') }}" method="POST">
        @csrf
        <input type="hidden" name="order_id" value="{{ $order->id }}">
        <input type="hidden" name="employee_id" value="{{ auth()->user()->id }}">

        <div class="mb-4">
            <label class="block font-bold mb-1 text-gray-700">Remaining Balance to Pay</label>
            <div class="flex items-center border rounded bg-gray-50 p-2">
                <span class="font-bold mr-2">₱</span>
                <input type="number" name="amount" value="{{ $remainingBalance }}" readonly
                       class="w-full bg-transparent outline-none font-mono font-bold text-green-700">
            </div>
            <p class="text-xs text-gray-500 mt-1 italic">* This is the remaining 50% of the total charge.</p>
        </div>

        <div class="mb-4">
            <label class="block font-bold mb-1 text-gray-700">Payment Method</label>
            <select name="payment_method" required class="w-full border p-2 rounded">
                <option value="Cash">Cash</option>
                <option value="GCash">GCash</option>
                <option value="Bank Transfer">Bank Transfer</option>
            </select>
        </div>

        <div class="mb-6">
            <label class="block font-bold mb-1 text-gray-700">Reference Number (Optional)</label>
            <input type="text" name="reference_number" placeholder="e.g. GCash Ref #" class="w-full border p-2 rounded">
        </div>

        <div class="flex gap-4">
            <button type="submit" class="bg-green-600 text-white font-bold px-6 py-2 rounded hover:bg-green-700">
                Post Final Payment
            </button>
            <a href="{{ route('orders.index') }}" class="text-gray-600 px-6 py-2">Cancel</a>
        </div>
    </form>
</div>
@endsection