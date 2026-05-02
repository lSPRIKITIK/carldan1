@extends('layouts.app')

@section('content')
    <h2 class="text-2xl font-bold mb-6">Add New Raw Material</h2>

    <form action="{{ route('materials.store') }}" method="POST" class="max-w-lg bg-white p-6 rounded border">
        @csrf

        <div class="mb-4">
            <label class="block font-bold mb-1 text-gray-700">Material Name</label>
            <input type="text" name="name" required placeholder="e.g., Mahogany Wood Base" class="w-full border p-2 rounded focus:ring focus:ring-blue-200">
        </div>

        <div class="mb-4">
            <label class="block font-bold mb-1 text-gray-700">Type Category</label>
            <input type="text" name="type" required placeholder="e.g., Wood, Plastic, Hardware" class="w-full border p-2 rounded focus:ring focus:ring-blue-200">
        </div>

        <div class="mb-6">
            <label class="block font-bold mb-1 text-gray-700">Unit Cost (PHP)</label>
            <input type="number" name="unit_cost" step="0.01" min="0" required placeholder="150.00" class="w-full border p-2 rounded focus:ring focus:ring-blue-200">
        </div>

        <div class="flex gap-4 mt-6">
            <button type="submit" class="bg-blue-600 text-white font-bold px-6 py-2 rounded hover:bg-blue-700 transition">Save Material</button>
            <a href="{{ route('materials.index') }}" class="text-gray-600 font-bold px-6 py-2 rounded hover:bg-gray-100 transition">Cancel</a>
        </div>
    </form>
@endsection