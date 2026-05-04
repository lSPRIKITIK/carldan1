@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-6">
    <!-- Back Button -->
    <div class="mb-4 d-print-none">
        <a href="{{ route('payments.index') }}" class="text-sm font-bold text-gray-500 hover:text-gray-800 flex items-center gap-2 w-fit px-4 py-2 bg-white rounded border shadow-sm transition">
            ← Back to Payments
        </a>
    </div>

    <!-- Header Card -->
    <div class="bg-white rounded-xl border shadow-sm p-6 mb-6">
        <div class="flex flex-col md:flex-row justify-between md:items-start gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Order #{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</h2>
                <p class="text-gray-500">{{ $order->client->first_name }} {{ $order->client->last_name }}</p>
            </div>
            <div class="text-right">
                <span class="px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider {{ $order->remaining_balance <= 0 ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-800' }}">
                    {{ $order->remaining_balance <= 0 ? 'Fully Paid' : 'Balance Pending' }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8 border-t pt-6">
            <div>
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-1">Products Included</span>
                @foreach($order->products as $product)
                    <p class="font-bold text-sm text-gray-800">{{ $product->name }} <span class="text-gray-500 font-normal">(x{{ $product->pivot->quantity }})</span></p>
                @endforeach
            </div>
            <div class="md:text-center">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-1">Total Contract Price</span>
                <p class="text-xl font-bold text-blue-600">₱ {{ number_format($order->total_amount, 2) }}</p>
            </div>
            <div class="md:text-end">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-1">Remaining Balance</span>
                <p class="text-xl font-bold {{ $order->remaining_balance <= 0 ? 'text-green-600' : 'text-600' }}">
                    ₱ {{ number_format(max(0, $order->remaining_balance), 2) }}
                </p>
            </div>
        </div>
    </div>

    <!-- Payment History Table -->
    <div class="bg-white rounded-xl border shadow-sm p-6">
        <h3 class="font-bold text-gray-800 mb-4 text-lg">Payment History</h3>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-500 font-bold uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="p-3 border-b">Date</th>
                        <th class="p-3 border-b">Method</th>
                        <th class="p-3 border-b">Reference #</th>
                        <th class="p-3 border-b">Processed By</th>
                        <th class="p-3 border-b text-right">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($order->payments as $payment)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-3 text-gray-600">
                            {{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}
                        </td>
                        <td class="p-3">
                            <span class="font-semibold text-gray-800">{{ $payment->payment_method }}</span>
                            @if($payment->reference_number === 'DOWNPAYMENT')
                                <span class="ml-2 text-[9px] bg-orange-100 text-orange-600 px-1.5 py-0.5 rounded uppercase font-bold">50% Down</span>
                            @endif
                        </td>
                        <td class="p-3 text-gray-500 font-mono text-xs">
                            <!-- Show N/A if it's blank or just 'SETTLEMENT'/'DOWNPAYMENT' from the seeder -->
                            @if($payment->reference_number === 'DOWNPAYMENT' || $payment->reference_number === 'SETTLEMENT' || !$payment->reference_number)
                                <span class="text-gray-300 italic">N/A</span>
                            @else
                                <span class="bg-gray-100 px-2 py-1 rounded text-gray-700">{{ $payment->reference_number }}</span>
                            @endif
                        </td>
                        <td class="p-3 text-gray-600">
                            {{ $payment->employee->first_name ?? 'System' }} {{ $payment->employee->last_name ?? '' }}
                        </td>
                        <td class="p-3 text-right font-bold text-green-700 tracking-wide">
                            ₱ {{ number_format($payment->amount, 2) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-6 text-center text-gray-500 italic">No payments recorded yet.</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="border-t-2 border-gray-200 bg-gray-50">
                    <tr>
                        <td colspan="4" class="p-4 text-right font-bold text-gray-700 uppercase text-xs tracking-wider">Total Collected:</td>
                        <td class="p-4 text-right font-bold text-blue-600 text-lg tracking-wide">
                            ₱ {{ number_format($order->amount_paid, 2) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Actions Section -->
    <div class="mt-6 flex justify-end d-print-none">
        @if($order->remaining_balance > 0)
            <a href="{{ route('payments.create', ['order_id' => $order->id]) }}" 
               class="bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 px-6 rounded transition shadow-sm flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Process Final Payment
            </a>
        @endif
    </div>
</div>
@endsection