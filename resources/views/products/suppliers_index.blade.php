@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Suppliers</h2>
    <a href="{{ route('suppliers.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">+ Add Supplier</a>
</div>
<div class="bg-white rounded border shadow-sm">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-100 text-gray-700 font-bold uppercase text-xs">
            <tr>
                <th class="p-4 border-b w-20">ID</th>
                <th class="p-4 border-b">Name</th>
                <th class="p-4 border-b">Contact</th>
                <th class="p-4 border-b">Address</th>
                <th class="p-4 border-b text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($suppliers as $supplier)
                <tr class="hover:bg-gray-50 transition">
                    <td class="p-4 text-gray-500 font-mono">#{{ $supplier->id }}</td>
                    <td class="p-4 font-bold text-gray-900">{{ $supplier->supplier_name }}</td>
                    <td class="p-4 text-gray-600">{{ $supplier->supplier_contact }}</td>
                    <td class="p-4 text-gray-700">{{ $supplier->supplier_street }}, {{ $supplier->supplier_city }}</td>
                    <td class="p-4 text-center">
                        <div class="flex justify-center gap-4">
                            <a href="{{ route('suppliers.show', $supplier->id) }}" class="text-blue-500 hover:text-blue-700 font-bold text-xs uppercase tracking-tight">View</a>
                            <a href="{{ route('suppliers.edit', $supplier->id) }}" class="text-indigo-500 hover:text-indigo-700 font-bold text-xs uppercase tracking-tight">Edit</a>
                            <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this supplier?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 font-bold text-xs uppercase tracking-tight bg-transparent border-none p-0 cursor-pointer">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection