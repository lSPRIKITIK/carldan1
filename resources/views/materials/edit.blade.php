@extends('layouts.app')

@section('content')
    <h2 class="text-2xl font-bold mb-6">Edit Material: {{ $material->name }}</h2>

    <form action="{{ route('materials.update', $material->id) }}" method="POST" class="max-w-lg bg-white p-6 rounded border">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block font-bold mb-1 text-gray-700">Material Name</label>
            <input type="text" name="name" value="{{ $material->name }}" required class="w-full border p-2 rounded focus:ring focus:ring-blue-200">
        </div>

        <div class="mb-4">
            <label class="block font-bold mb-1 text-gray-700">Type Category</label>
            <input type="text" name="type" value="{{ $material->type }}" required class="w-full border p-2 rounded focus:ring focus:ring-blue-200">
        </div>

        <div class="mb-6">
            <label class="block font-bold mb-1 text-gray-700">Unit Cost (₱)</label>
            <input type="number" name="unit_cost" step="0.01" min="0" value="{{ $material->unit_cost }}" required class="w-full border p-2 rounded focus:ring focus:ring-blue-200">
        </div>

        <div class="flex gap-4 mt-6">
            <button type="submit" class="bg-blue-600 text-white font-bold px-6 py-2 rounded hover:bg-blue-700 transition">Update Material</button>
            <a href="{{ route('materials.index') }}" class="flex items-center text-gray-600 font-bold px-6 py-2 rounded hover:bg-gray-100 transition">Cancel</a>
        </div>
    </form>
@endsection