@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-6">
    <!-- Back Button -->
    <div class="mb-4 d-print-none">
        <a href="{{ route('products.index') }}" class="text-sm font-bold text-gray-500 hover:text-gray-800 flex items-center gap-2 w-fit px-4 py-2 bg-white rounded border shadow-sm transition">
            ← Back to Inventory
        </a>
    </div>

    <!-- Header & Readiness Card -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Product Info -->
        <div class="bg-white rounded-xl border shadow-sm p-6 col-span-2">
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-1">Product Details</span>
            <div class="flex justify-between items-start mt-2">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $product->name }}</h2>
                    <p class="text-gray-500 mt-1">Category: <span class="bg-gray-100 text-gray-700 px-2 py-0.5 rounded text-xs font-semibold">{{ $product->type }}</span></p>
                </div>
                <div class="text-right">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-1">Selling Price</span>
                    <p class="text-2xl font-bold text-blue-600">₱ {{ number_format($product->price, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Manufacturing Readiness -->
        <div class="bg-white rounded-xl border shadow-sm p-6 flex flex-col justify-center items-center text-center">
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-2">Max Buildable Qty</span>
            
            @if($product->materials->count() == 0)
                <h3 class="text-3xl font-bold text-gray-300">N/A</h3>
                <p class="text-xs text-gray-400 mt-2">No materials assigned.</p>
            @elseif($maxBuildable > 0)
                <h3 class="text-5xl font-bold text-green-500">{{ $maxBuildable }}</h3>
                <p class="text-xs text-green-600 mt-2 font-semibold">Ready to manufacture</p>
            @else
                <h3 class="text-5xl font-bold text-red-500">0</h3>
                <p class="text-xs text-red-600 mt-2 font-semibold">Insufficient materials</p>
            @endif
        </div>
    </div>

    <!-- Bill of Materials Table -->
    <div class="bg-white rounded-xl border shadow-sm p-6">
        <h3 class="font-bold text-gray-800 mb-4 text-lg">Bill of Materials (Required Stocks)</h3>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-500 font-bold uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="p-3 border-b">Material Name</th>
                        <th class="p-3 border-b">Type</th>
                        <th class="p-3 border-b text-center">Required per Item</th>
                        <th class="p-3 border-b text-center">Current Total Stock</th>
                        <th class="p-3 border-b text-center">Stock Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($product->materials as $material)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-3 text-gray-900 font-semibold">{{ $material->name }}</td>
                        <td class="p-3 text-gray-500 text-xs">{{ $material->type }}</td>
                        <td class="p-3 text-center font-bold text-gray-700">
                            {{ $material->pivot->required_quantity }}
                        </td>
                        <td class="p-3 text-center font-mono font-semibold {{ $material->total_stock < $material->pivot->required_quantity ? 'text-red-500' : 'text-blue-600' }}">
                            {{ $material->total_stock }}
                        </td>
                        <td class="p-3 text-center">
                            @if($material->total_stock >= $material->pivot->required_quantity)
                                <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-bold uppercase">Sufficient</span>
                            @else
                                <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-bold uppercase">Need More</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-6 text-center text-gray-500 italic">No materials have been added to this product's formula yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection