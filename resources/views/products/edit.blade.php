@extends('layouts.app')

@section('content')
    <h2 class="text-2xl font-bold mb-6">Edit Product: {{ $product->name }}</h2>

    <form action="{{ route('products.update', $product->id) }}" method="POST" class="max-w-2xl border p-6 rounded bg-white">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block font-bold mb-1">Product Name</label>
                <input type="text" name="name" value="{{ $product->name }}" required class="w-full border p-2 rounded">
            </div>
            <div>
                <label class="block font-bold mb-1">Product Type</label>
                <input type="text" name="type" value="{{ $product->type }}" required class="w-full border p-2 rounded">
            </div>
        </div>

        <div class="mb-6">
            <label class="block font-bold mb-1">Selling Price (PHP)</label>
            <input type="number" name="price" value="{{ $product->price }}" step="0.01" min="0" required class="w-1/3 border p-2 rounded">
        </div>

        <div class="mb-6 border p-4 rounded bg-gray-50">
            <h3 class="font-bold mb-3 text-lg">Bill of Materials (Recipe)</h3>
            
            <div id="material-container" class="flex flex-col gap-3">
                <!-- Loop through existing materials to pre-fill the form -->
                @foreach($product->materials as $index => $prodMat)
                    <div class="material-row flex gap-2">
                        <select name="materials[{{ $index }}][material_id]" required class="w-2/3 border p-2 rounded">
                            @foreach($materials as $material)
                                <option value="{{ $material->id }}" {{ $material->id == $prodMat->id ? 'selected' : '' }}>
                                    {{ $material->name }} (Cost: {{ $material->unit_cost }})
                                </option>
                            @endforeach
                        </select>
                        <input type="number" name="materials[{{ $index }}][required_quantity]" value="{{ $prodMat->pivot->required_quantity }}" required min="1" class="w-1/3 border p-2 rounded">
                        <button type="button" class="remove-btn bg-red-500 text-white px-3 rounded font-bold hover:bg-red-600">X</button>
                    </div>
                @endforeach
            </div>

            <button type="button" id="add-material-btn" class="mt-4 bg-gray-200 text-gray-800 px-4 py-2 rounded font-bold hover:bg-gray-300">
                + Add Another Material
            </button>
        </div>

        <div class="flex gap-4 mt-6">
            <button type="submit" class="bg-blue-600 text-white font-bold px-6 py-2 rounded hover:bg-blue-700">Update Recipe</button>
            <a href="{{ route('products.index') }}" class="flex items-center text-gray-600 font-bold px-6 py-2 rounded hover:bg-gray-100">Cancel</a>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Set the row count based on how many items already exist
            let rowCount = {{ count($product->materials) }}; 
            const container = document.getElementById('material-container');
            
            document.getElementById('add-material-btn').addEventListener('click', function() {
                const firstRow = container.querySelector('.material-row');
                const newRow = firstRow.cloneNode(true);
                
                newRow.querySelector('select').name = `materials[${rowCount}][material_id]`;
                newRow.querySelector('select').value = ''; 
                
                newRow.querySelector('input').name = `materials[${rowCount}][required_quantity]`;
                newRow.querySelector('input').value = '';
                
                container.appendChild(newRow);
                rowCount++;
            });

            container.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-btn')) {
                    if (container.querySelectorAll('.material-row').length > 1) {
                        e.target.closest('.material-row').remove();
                    } else {
                        alert('A product must have at least one material.');
                    }
                }
            });
        });
    </script>
@endsection