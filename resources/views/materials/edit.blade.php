@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-6">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Edit Material</h2>

    <!-- This block will catch and display any hidden errors! -->
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li class="text-xs font-bold">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('materials.update', $material->id) }}" method="POST" class="bg-white p-6 rounded-xl border shadow-sm">
        @csrf
        @method('PUT') <!-- Required for updating in Laravel -->

        <div class="mb-4">
            <label class="block font-bold mb-1 text-gray-700 text-sm">Material Name</label>
            <input type="text" name="name" value="{{ old('name', $material->name) }}" required 
                   class="border p-2 rounded w-full focus:ring-2 focus:ring-blue-300 outline-none">
        </div>

        <div class="mb-4">
            <label class="block font-bold mb-1 text-gray-700 text-sm">Type Category</label>
            <input type="text" name="type" value="{{ old('type', $material->type) }}" required 
                   class="border p-2 rounded w-full focus:ring-2 focus:ring-blue-300 outline-none">
        </div>

        <!-- THE FIX: Ensure name is 'unit_cost' and value pulls from $material->price -->
        <div class="mb-8">
            <label class="block font-bold mb-1 text-gray-700 text-sm">Unit Cost (₱)</label>
            <input type="number" name="unit_cost" value="{{ old('unit_cost', $material->price) }}" step="0.01" min="0" required 
                   class="border p-2 rounded w-full focus:ring-2 focus:ring-blue-300 outline-none">
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="bg-blue-600 text-white font-bold px-8 py-2.5 rounded hover:bg-blue-700 transition shadow-sm">
                Update Material
            </button>
            <a href="{{ route('materials.index') }}" class="text-sm font-bold text-gray-500 hover:text-gray-800 px-4 py-2">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection