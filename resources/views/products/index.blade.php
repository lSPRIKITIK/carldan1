@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Products Inventory</h2>
        <a href="{{ route('products.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded transition">
            Add New Product
        </a>
    </div>

    <div class="bg-white rounded border overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-200 text-gray-700 font-bold">
                <tr>
                    <th class="p-3 border-b w-16">ID</th>
                    <th class="p-3 border-b">Product Name</th>
                    <th class="p-3 border-b">Type</th>
                    <th class="p-3 border-b">Materials Used (Recipe)</th>
                    <th class="p-3 border-b">Price</th>
                    <th class="p-3 border-b text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-3 border-b text-gray-500">{{ $product->id }}</td>
                        <td class="p-3 border-b font-bold text-gray-900">{{ $product->name }}</td>
                        <td class="p-3 border-b text-gray-600">{{ $product->type }}</td>
                        <td class="p-3 border-b">
                            <div class="flex flex-wrap gap-2">
                                @foreach($product->materials as $material)
                                    <span class="bg-gray-100 border text-gray-600 text-xs px-2 py-1 rounded">
                                        {{ $material->name }} ({{ $material->pivot->required_quantity }})
                                    </span>
                                @endforeach
                            </div>
                        </td>
                        <td class="p-3 border-b text-gray-900">PHP {{ number_format($product->price, 2) }}</td>
                        <td class="p-3 border-b text-center">
                            <a href="{{ route('products.edit', $product->id) }}" class="text-blue-500 hover:underline mr-3">Edit</a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline cursor-pointer font-bold bg-transparent border-none p-0">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-6 border-b text-center text-gray-500">No products found. Build your first product!</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection