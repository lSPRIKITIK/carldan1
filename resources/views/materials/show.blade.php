@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold">{{ $material->name }}</h2>
        <a href="{{ route('materials.index') }}" class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded transition">← Back to Materials</a>
    </div>

    <div class="bg-white border rounded p-6 mb-6">
        <div class="grid grid-cols-3 gap-4">
            <div>
                <span class="text-xs text-gray-400">Type</span>
                <div class="font-bold text-lg">{{ $material->type }}</div>
            </div>
            <div>
                <span class="text-xs text-gray-400">Unit Cost</span>
                <div class="font-bold text-lg">₱{{ number_format($material->price, 2) }}</div>
            </div>
            <div>
                <span class="text-xs text-gray-400">Total Current Stock</span>
                <div class="font-bold text-lg">{{ $material->stocks->sum('quantity') }} units</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Stock IN Table -->
        <div class="bg-white border rounded overflow-hidden">
            <div class="bg-gray-100 px-4 py-3 border-b font-bold text-gray-800">Stock IN (Restock Records)</div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-700 font-bold text-xs uppercase border-b">
                        <tr>
                            <th class="p-3">Batch</th>
                            <th class="p-3">Date</th>
                            <th class="p-3">Supplier</th>
                            <th class="p-3 text-right">Qty In</th>
                            <th class="p-3 text-right">Remaining</th>
                            <th class="p-3 text-right">Unit Cost</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($material->stocks as $stock)
                            <tr class="hover:bg-gray-50 border-b">
                                <td class="p-3 text-gray-600">{{ $stock->batch_number }}</td>
                                <td class="p-3 text-gray-600">{{ \Carbon\Carbon::parse($stock->created_at)->format('M d, Y') }}</td>
                                <td class="p-3 text-gray-800">{{ $stock->supplier->supplier_name ?? 'Unknown' }}</td>
                                <td class="p-3 text-right font-semibold">{{ $stock->stock_in }}</td>
                                <td class="p-3 text-right font-semibold">{{ $stock->quantity }}</td>
                                <td class="p-3 text-right">₱{{ number_format($stock->unit_cost, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-6 text-center text-gray-500 italic">No restock records yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Stock OUT Table -->
        <div class="bg-white border rounded overflow-hidden">
            <div class="bg-gray-100 px-4 py-3 border-b font-bold text-gray-800">Stock OUT (Usage in Orders)</div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-700 font-bold text-xs uppercase border-b">
                        <tr>
                            <th class="p-3">Stock Batch</th>
                            <th class="p-3 text-right">Qty Out</th>
                            <th class="p-3 text-right">Qty Remaining</th>
                            <th class="p-3">Deducted When</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $hasStockOut = false;
                            foreach($material->stocks as $stock) {
                                $qtyOut = $stock->stock_in - $stock->quantity;
                                if ($qtyOut > 0) {
                                    $hasStockOut = true;
                                }
                            }
                        @endphp
                        
                        @forelse($material->stocks as $stock)
                            @php $qtyOut = $stock->stock_in - $stock->quantity; @endphp
                            @if($qtyOut > 0)
                                <tr class="hover:bg-gray-50 border-b">
                                    <td class="p-3 text-gray-600">{{ $stock->batch_number }}</td>
                                    <td class="p-3 text-right font-semibold text-red-600">{{ $qtyOut }}</td>
                                    <td class="p-3 text-right font-semibold">{{ $stock->quantity }}</td>
                                    <td class="p-3 text-gray-600">{{ \Carbon\Carbon::parse($stock->updated_at)->format('M d, Y') }}</td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="4" class="p-6 text-center text-gray-500 italic">No stock-out records yet.</td>
                            </tr>
                        @endforelse

                        @if(!$hasStockOut)
                            <tr>
                                <td colspan="4" class="p-6 text-center text-gray-500 italic">No stock has been used in orders yet.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
