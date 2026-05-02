@extends('layouts.app')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
    <h2 class="text-2xl font-bold text-gray-800">Products Inventory</h2>
    <div class="flex items-center gap-4 w-full md:w-auto">
        <!-- Search Form -->
        <form action="{{ route('products.index') }}" method="GET" class="flex items-center gap-2">
            <div class="relative w-64">
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Search Product Name" 
                    class="border p-2 pr-10 rounded w-full shadow-sm focus:ring-2 focus:ring-blue-300 outline-none">
                @if(request('search'))
                    <a href="{{ route('products.index') }}" class="absolute right-3 top-2.5 text-gray-400 hover:text-red-500 transition" title="Clear Search">
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
        <a href="{{ route('products.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition shadow-sm whitespace-nowrap">
            + Create New Product
        </a>
    </div>
</div>

<div class="bg-white rounded border shadow-sm">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-100 text-gray-700 font-bold uppercase text-xs">
            <tr>
                <th class="p-4 border-b w-20">ID</th>
                <th class="p-4 border-b">Name</th>
                <th class="p-4 border-b">Type</th>
                <th class="p-4 border-b">Price</th>
                <th class="p-4 border-b text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr class="hover:bg-gray-50 transition">
                    <td class="p-4 text-gray-500 font-mono">#{{ $product->id }}</td>
                    <td class="p-4 font-bold text-gray-900">{{ $product->name }}</td>
                    <td class="p-4 text-gray-600">
                        <span class="bg-gray-100 px-2 py-1 rounded text-xs">{{ $product->type }}</span>
                    </td>
                    <td class="p-4 font-mono font-semibold text-gray-900">
                        ₱ {{ number_format($product->price, 2) }}
                    </td>
                    <td class="p-4 text-center">
                        <div class="flex justify-center gap-4">
                            <a href="{{ route('products.edit', $product->id) }}" class="text-blue-500 hover:text-blue-700 font-bold text-xs uppercase tracking-tight">
                                Edit
                            </a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 font-bold text-xs uppercase tracking-tight bg-transparent border-none p-0 cursor-pointer">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $products->links() }}
</div>
@endsection