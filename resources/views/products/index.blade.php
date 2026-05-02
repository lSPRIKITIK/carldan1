@extends('layouts.app')

@section('content')
<div>
    <h2 class="text-2xl font-bold text-gray-800">Products Inventory</h2>
</div>
<div class="flex justify-between items-center mb-6">
    <form action="{{ route('products.index') }}" method="GET" class="flex gap-2">
        <div class="relative">
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Search Products" 
                   class="border p-2 pr-10 rounded w-80 shadow-sm outline-none focus:ring-2 focus:ring-blue-300">
            @if(request('search'))
                <a href="{{ route('products.index') }}" class="absolute right-3 top-2.5 text-gray-400 hover:text-red-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </a>
            @endif
        </div>
        <button type="submit" class="bg-gray-800 text-white px-5 py-2 rounded font-bold hover:bg-gray-900 transition">Search</button>
    </form>
    <a href="{{ route('products.create') }}" class="bg-blue-600 text-white font-bold py-2 px-4 rounded shadow-sm">+ Add Product</a>
</div>

<div class="bg-white rounded border shadow-sm">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-100 text-gray-700 font-bold uppercase text-xs">
            <tr>
                <th class="p-4 border-b">Name</th>
                <th class="p-4 border-b">Type</th>
                <th class="p-4 border-b">Price</th>
                <th class="p-4 border-b text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr class="hover:bg-gray-50">
                    <td class="p-4 font-bold">{{ $product->name }}</td>
                    <td class="p-4 text-gray-600">{{ $product->type }}</td>
                    <td class="p-4 font-mono">PHP {{ number_format($product->price, 2) }}</td>
                    <td class="p-4 text-center">
                        <a href="{{ route('products.edit', $product->id) }}" class="text-blue-500 font-bold mr-3">Edit</a>
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this product?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:underline cursor-pointer font-bold bg-transparent border-none p-0">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="mt-6">{{ $products->links() }}</div>
@endsection