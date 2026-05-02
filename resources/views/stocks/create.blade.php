@extends('layouts.app')

@section('content')
    <h2 class="text-2xl font-bold mb-6">Restock Material</h2>

    <form action="{{ route('stocks.store') }}" method="POST" class="max-w-lg bg-white p-6 rounded border shadow-sm">
        @csrf

        <!-- Hidden ID for the database -->
        <input type="hidden" name="material_id" value="{{ $material->id }}">

        <!-- Read-only Display for the User -->
        <div class="mb-4">
            <label class="block font-bold mb-1 text-gray-700">Material to Restock</label>
            <input type="text" value="{{ $material->name }}" disabled 
                   class="w-full border p-2 rounded bg-gray-100 text-gray-600 cursor-not-allowed font-semibold">
            <p class="text-xs text-gray-500 mt-1">Current Stock: {{ $material->stocks->last()->quantity ?? 0 }} units</p>
        </div>

        <!-- Supplier Selection (Required by your ERD) -->
        <div class="mb-4">
            <label class="block font-bold mb-1 text-gray-700">Supplier</label>
            <select name="supplier_id" required class="w-full border p-2 rounded focus:ring focus:ring-blue-200">
                <option value="" disabled selected>Select Supplier...</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}">{{ $supplier->supplier_name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Amount -->
        <div class="mb-6">
            <label class="block font-bold mb-1 text-gray-700">Quantity to Add (Stock IN)</label>
            <input type="number" name="amount" min="1" required autofocus 
                   placeholder="Enter amount..." class="w-full border p-2 rounded focus:ring focus:ring-blue-200">
        </div>

        <div class="flex gap-4">
            <button type="submit" class="bg-green-600 text-white font-bold px-6 py-2 rounded hover:bg-green-700 transition">
                Confirm Restock
            </button>
            <a href="{{ route('materials.index') }}" class="flex items-center text-gray-600 font-bold px-6 py-2 rounded hover:bg-gray-100 transition">
                Cancel
            </a>
        </div>
    </form>
@endsection