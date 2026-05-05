@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-6">
    <h2 class="text-2xl font-bold mb-4">Edit Payment</h2>
    <form action="{{ route('payments.update', $payment->id) }}" method="POST" class="bg-white p-6 rounded border">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block font-bold mb-1">Order</label>
            <select name="order_id" required class="w-full border p-2 rounded">
                @foreach($orders as $order)
                    <option value="{{ $order->id }}" {{ $order->id == $payment->order_id ? 'selected' : '' }}>
                        #{{ str_pad($order->id,3,'0',STR_PAD_LEFT) }} — {{ $order->client->first_name ?? '' }} {{ $order->client->last_name ?? '' }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="block font-bold mb-1">Processed By</label>
            <select name="employee_id" required class="w-full border p-2 rounded">
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}" {{ $employee->id == $payment->employee_id ? 'selected' : '' }}>
                        {{ $employee->first_name }} {{ $employee->last_name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="block font-bold mb-1">Payment Method</label>
            <input type="text" name="payment_method" value="{{ old('payment_method', $payment->payment_method) }}" required class="w-full border p-2 rounded" />
        </div>
        <div class="mb-4">
            <label class="block font-bold mb-1">Payment Date</label>
            <input type="date" name="payment_date" value="{{ old('payment_date', \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d')) }}" required class="w-full border p-2 rounded" />
        </div>
        <div class="mb-4">
            <label class="block font-bold mb-1">Amount</label>
            <input type="number" step="0.01" name="amount" value="{{ old('amount', $payment->amount) }}" required class="w-full border p-2 rounded" />
        </div>
        <div class="mb-4">
            <label class="block font-bold mb-1">Reference Number</label>
            <input type="text" name="reference_number" value="{{ old('reference_number', $payment->reference_number) }}" class="w-full border p-2 rounded" />
        </div>
        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Save</button>
            <a href="{{ route('payments.index') }}" class="px-4 py-2 rounded border">Cancel</a>
        </div>
    </form>
</div>
@endsection
