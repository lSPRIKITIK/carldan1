@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Dashboard</h2>
</div>

<!-- Summary Cards[cite: 20] -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    
    <!-- Total Revenue Card -->
    <div class="bg-white rounded-xl border shadow-sm p-6 flex justify-between items-center transition hover:shadow-md">
        <div>
            <h6 class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">Total Revenue</h6>
            <h3 class="text-2xl font-bold text-gray-900">₱{{ number_format($totalRevenue, 2) }}</h3>
        </div>
        <div class="bg-blue-50 p-3 rounded-full text-blue-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
            </svg>
        </div>
    </div>

    <!-- Sales / Collected Card -->
    <div class="bg-white rounded-xl border shadow-sm p-6 flex justify-between items-center transition hover:shadow-md">
        <div>
            <h6 class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">Sales Collected</h6>
            <h3 class="text-2xl font-bold text-green-600">₱{{ number_format($totalCollected, 2) }}</h3>
        </div>
        <div class="bg-green-50 p-3 rounded-full text-green-600 font-bold text-xl h-12 w-12 flex items-center justify-center">
            ₱
        </div>
    </div>

    <!-- Pending Card -->
    <div class="bg-white rounded-xl border shadow-sm p-6 flex justify-between items-center transition hover:shadow-md">
        <div>
            <h6 class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">Pending Balance</h6>
            <h3 class="text-2xl font-bold text-orange-500">₱{{ number_format($totalPending, 2) }}</h3>
        </div>
        <div class="bg-orange-50 p-3 rounded-full text-orange-500 font-bold text-xl h-12 w-12 flex items-center justify-center">
            ₱
        </div>
    </div>

</div>

<!-- Recent Sales Records Table[cite: 20] -->
<div class="bg-white rounded-xl border shadow-sm p-6">
    <h3 class="font-bold text-gray-800 mb-4 text-lg">Recent Sales Records</h3>
    
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-500 font-bold uppercase text-[10px] tracking-wider">
                <tr>
                    <th class="p-3 border-b">Order ID</th>
                    <th class="p-3 border-b">Client</th>
                    <th class="p-3 border-b">Products</th>
                    <th class="p-3 border-b text-right">Total</th>
                    <th class="p-3 border-b text-right">Collected</th>
                    <th class="p-3 border-b text-right">Balance</th>
                    <th class="p-3 border-b text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($recentOrders as $record)
                <tr class="hover:bg-gray-50 transition">
                    <td class="p-3 text-gray-600 font-mono font-semibold">#{{ str_pad($record->id, 3, '0', STR_PAD_LEFT) }}</td>
                    <td class="p-3 text-gray-900 font-semibold">{{ $record->client->first_name ?? '' }} {{ $record->client->last_name ?? '' }}</td>
                    <td class="p-3 text-gray-600">
                        @foreach($record->products as $product)
                            <div class="text-xs">{{ $product->name }} <span class="text-gray-400">(x{{ $product->pivot->quantity }})</span></div>
                        @endforeach
                    </td>
                    <td class="p-3 text-right font-bold text-gray-800">₱{{ number_format($record->total, 2) }}</td>
                    <td class="p-3 text-right font-semibold text-green-600">₱{{ number_format($record->paid, 2) }}</td>
                    <td class="p-3 text-right font-semibold text-orange-500">₱{{ number_format($record->balance, 2) }}</td>
                    <td class="p-3 text-center">
                        @if($record->status === 'Paid')
                            <span class="bg-green-100 text-green-700 px-2.5 py-1 rounded-full text-xs font-bold uppercase">Paid</span>
                        @else
                            <span class="bg-orange-100 text-orange-700 px-2.5 py-1 rounded-full text-xs font-bold uppercase">{{ $record->status }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="p-6 text-center text-gray-500 italic">No recent sales records found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">
        {{ $recentOrders->links() }}
    </div>
</div>
@endsection