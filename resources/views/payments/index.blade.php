@extends('layouts.app')

@section('content')
<div>
    <h2 class="text-2xl font-bold text-gray-800">Payments & Transactions</h2>
</div>
<div class="flex justify-between items-center mb-6">
    <form action="{{ route('payments.index') }}" method="GET" class="flex gap-2">
        <div class="relative">
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Ref # or Client Name..." 
                   class="border p-2 pr-10 rounded w-80 shadow-sm outline-none focus:ring-2 focus:ring-blue-300">
            @if(request('search'))
                <a href="{{ route('payments.index') }}" class="absolute right-3 top-2.5 text-gray-400 hover:text-red-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </a>
            @endif
        </div>
        <button type="submit" class="bg-gray-800 text-white px-5 py-2 rounded font-bold hover:bg-gray-900 transition">Search</button>
    </form>
</div>

<div class="bg-white rounded border shadow-sm overflow-hidden">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-100 text-gray-700 font-bold uppercase text-xs">
            <tr>
                <th class="p-4 border-b">Ref Number</th>
                <th class="p-4 border-b">Client</th>
                <th class="p-4 border-b">Order ID</th>
                <th class="p-4 border-b">Amount</th>
                <th class="p-4 border-b">Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $payment)
                <tr class="hover:bg-gray-50">
                    <td class="p-4 font-mono text-blue-600 font-bold">{{ $payment->reference_number }}</td>
                    <td class="p-4">{{ $payment->order->client->first_name }} {{ $payment->order->client->last_name }}</td>
                    <td class="p-4">PO-{{ str_pad($payment->order_id, 3, '0', STR_PAD_LEFT) }}</td>
                    <td class="p-4 font-bold">PHP {{ number_format($payment->amount, 2) }}</td>
                    <td class="p-4 text-gray-600">{{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="p-10 text-center text-gray-500">No payment records found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">{{ $payments->links() }}</div>
@endsection