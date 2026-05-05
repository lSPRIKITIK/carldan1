@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold">{{ $material->name }}</h2>
        <a href="{{ route('materials.index') }}" class="bg-gray-200 px-3 py-1 rounded">Back to Materials</a>
    </div>

    <div class="bg-white border rounded p-6 mb-6">
        <div class="grid grid-cols-3 gap-4">
            <div>
                <span class="text-xs text-gray-400">Type</span>
                <div class="font-bold">{{ $material->type }}</div>
            </div>
            <div>
                <span class="text-xs text-gray-400">Unit Cost</span>
                <div class="font-bold">₱{{ number_format($material->price,2) }}</div>
            </div>
            <div>
                <span class="text-xs text-gray-400">Total Stock</span>
                <div class="font-bold">{{ $material->stocks->sum('quantity') }} units</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white border rounded p-4">
            <h3 class="font-bold text-lg mb-3">Stock In (Restock Records)</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-100 text-gray-700 font-bold text-xs">
                        <tr>
                            <th class="p-2 border-b">Date</th>
                            <th class="p-2 border-b">Supplier</th>
                            <th class="p-2 border-b text-right">Qty In</th>
                            <th class="p-2 border-b text-right">Remaining Qty</th>
                            <th class="p-2 border-b text-right">Unit Cost</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($material->stocks as $stock)
                            <tr class="hover:bg-gray-50">
                                <td class="p-2 text-gray-600">{{ \Carbon\Carbon::parse($stock->created_at)->format('M d, Y') }}</td>
                                <td class="p-2 text-gray-800">{{ $stock->supplier->supplier_name ?? 'Unknown' }}</td>
                                <td class="p-2 text-right font-semibold">{{ $stock->stock_in }}</td>
                                <td class="p-2 text-right">{{ $stock->quantity }}</td>
                                <td class="p-2 text-right">₱{{ number_format($stock->unit_cost,2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-4 text-center text-gray-500 italic">No restock records.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white border rounded p-4">
            <h3 class="font-bold text-lg mb-3">Stock Out (Usage)</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-100 text-gray-700 font-bold text-xs">
                        <tr>
                            <th class="p-2 border-b">Date</th>
                            <th class="p-2 border-b">Order</th>
                            <th class="p-2 border-b text-right">Qty Out</th>
                            <th class="p-2 border-b">Stock Batch</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $outs = $material->stock_movements->where('movement_type', 'out'); @endphp
                        @forelse($outs as $out)
                            <tr class="hover:bg-gray-50">
                                <td class="p-2 text-gray-600">{{ \Carbon\Carbon::parse($out->created_at)->format('M d, Y') }}</td>
                                <td class="p-2 text-blue-600 font-semibold">
                                    @if($out->order)
                                        <a href="{{ route('orders.show', $out->order->id) }}">#{{ str_pad($out->order->id,3,'0',STR_PAD_LEFT) }}</a>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="p-2 text-right font-semibold">{{ $out->quantity }}</td>
                                <td class="p-2">{{ $out->stock_id ? 'Batch #' . $out->stock_id : '--' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-4 text-center text-gray-500 italic">No stock-out records.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
