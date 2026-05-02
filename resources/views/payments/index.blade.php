@extends('layouts.app')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
    <h2 class="text-2xl font-bold text-gray-800">Payment History</h2>
    
    <div class="flex items-center gap-4 w-full md:w-auto">
        <!-- Search Form -->
        <form action="{{ route('payments.index') }}" method="GET" class="flex items-center gap-2">
            <div class="relative w-64">
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Search Order # or Client..." 
                    class="border p-2 pr-10 rounded w-full shadow-sm focus:ring-2 focus:ring-blue-300 outline-none">
                
                @if(request('search'))
                    <a href="{{ route('payments.index') }}" class="absolute right-3 top-2.5 text-gray-400 hover:text-red-500 transition">
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
                <th class="p-4 border-b">Payment ID</th>
                <th class="p-4 border-b">Order Ref</th>
                <th class="p-4 border-b">Client</th>
                <th class="p-4 border-b">Amount Paid</th>
                <th class="p-4 border-b">Method</th>
                <th class="p-4 border-b">Reference #</th>
                <th class="p-4 border-b">Date</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($payments as $payment)
                <tr class="hover:bg-gray-50 transition">
                    <td class="p-4 text-gray-500 font-mono">#{{ $payment->id }}</td>
                    <td class="p-4 font-bold text-blue-600">
                        #{{ str_pad($payment->order_id, 3, '0', STR_PAD_LEFT) }}
                    </td>
                    <td class="p-4">
                        <p class="font-semibold text-gray-900">
                            {{ $payment->order->client->first_name }} {{ $payment->order->client->last_name }}
                        </p>
                    </td>
                    <td class="p-4">
                        <p class="font-mono font-bold text-green-700">
                            ₱ {{ number_format($payment->amount, 2) }}
                        </p>
                        @if($payment->reference_number === 'DOWNPAYMENT')
                            <span class="text-[10px] uppercase font-bold text-orange-500">50% Downpayment</span>
                        @endif
                    </td>
                    <td class="p-4">
                        <span class="px-2 py-1 bg-gray-100 rounded text-xs font-semibold text-gray-600">
                            {{ $payment->payment_method }}
                        </span>
                    </td>
                    <td class="p-4 text-gray-600 font-mono text-xs">
                        {{ $payment->reference_number ?? 'N/A' }}
                    </td>
                    <td class="p-4 text-gray-500 text-xs">
                        {{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="p-10 text-center text-gray-500 italic">
                        No payment records found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $payments->links() }}
</div>
@endsection