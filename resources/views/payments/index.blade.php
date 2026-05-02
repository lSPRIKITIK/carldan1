@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Payments & Transactions</h2>
        <a href="{{ route('payments.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded transition">
            Record New Payment
        </a>
    </div>

    <div class="bg-white rounded border overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-200 text-gray-700 font-bold">
                <tr>
                    <th class="p-3 border-b">Receipt ID</th>
                    <th class="p-3 border-b">Order Ref</th>
                    <th class="p-3 border-b">Client Name</th>
                    <th class="p-3 border-b">Method</th>
                    <th class="p-3 border-b">Amount (PHP)</th>
                    <th class="p-3 border-b">Date</th>
                    <th class="p-3 border-b">Processed By</th>
                    <th class="p-3 border-b text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-3 border-b text-gray-500">REC-{{ str_pad($payment->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td class="p-3 border-b font-bold text-blue-600">PO-{{ str_pad($payment->order_id, 3, '0', STR_PAD_LEFT) }}</td>
                        <td class="p-3 border-b font-semibold text-gray-900">{{ $payment->order->client->first_name }} {{ $payment->order->client->last_name }}</td>
                        <td class="p-3 border-b text-gray-600">
                            {{ $payment->payment_method }}
                            @if($payment->reference_number)
                                <br><span class="text-xs text-gray-400">Ref: {{ $payment->reference_number }}</span>
                            @endif
                        </td>
                        <td class="p-3 border-b font-bold text-green-600">{{ number_format($payment->amount, 2) }}</td>
                        <td class="p-3 border-b text-gray-600">{{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}</td>
                        <td class="p-3 border-b text-gray-600">{{ $payment->employee->first_name }}</td>
                        <td class="p-3 border-b text-center">
                            <a href="{{ route('payments.edit', $payment->id) }}" class="text-blue-500 hover:underline mr-3">Edit</a>
                            <span class="text-red-500 hover:underline cursor-pointer">Delete</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="p-6 border-b text-center text-gray-500">No payments recorded yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection