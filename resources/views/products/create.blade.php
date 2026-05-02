@extends('layouts.app')

@section('content')
    <h2 class="text-2xl font-bold mb-6">Create New Product</h2>

    <form action="{{ route('products.store') }}" method="POST" class="max-w-2xl">
        @csrf

        <!-- Product Basic Info -->
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block font-bold mb-1">Product Name</label>
                <input type="text" name="name" required placeholder="e.g., Premium Wood Plaque" class="w-full border p-2 rounded">
            </div>
            <div>
                <label class="block font-bold mb-1">Product Type</label>
                <input type="text" name="type" required placeholder="e.g., Plaque, Trophy" class="w-full border p-2 rounded">
            </div>
        </div>

        <div class="mb-6">
            <label class="block font-bold mb-1">Selling Price (PHP)</label>
            <input type="number" name="price" step="0.01" min="0" required placeholder="1500.00" class="w-1/3 border p-2 rounded">
        </div>

        <!-- The Bill of Materials Section -->
        <div class="mb-6 border p-4 rounded bg-gray-50">
            <h3 class="font-bold mb-3 text-lg">Bill of Materials</h3>
            <p class="text-sm text-gray-600 mb-4">Add the raw materials required to build exactly one unit of this product.</p>
            
            <div id="material-container" class="flex flex-col gap-3">
                <!-- Initial Material Row -->
                <div class="material-row flex gap-2">
                    <select name="materials[0][material_id]" required class="w-2/3 border p-2 rounded">
                        <option value="" disabled selected>Select a Raw Material...</option>
                        @foreach($materials as $material)
                            <option value="{{ $material->id }}">{{ $material->name }} (Cost: {{ $material->unit_cost }})</option>
                        @endforeach
                    </select>
                    <input type="number" name="materials[0][required_quantity]" placeholder="Qty Needed" required min="1" class="w-1/3 border p-2 rounded">
                    <button type="button" class="remove-btn bg-red-500 text-white px-3 rounded font-bold hover:bg-red-600 transition">X</button>
                </div>
            </div>

            <button type="button" id="add-material-btn" class="mt-4 bg-gray-200 text-gray-800 px-4 py-2 rounded font-bold hover:bg-gray-300 transition">
                + Add Another Material
            </button>
        </div>

        <div class="flex gap-4 mt-6">
            <button type="submit" class="bg-blue-600 text-white font-bold px-6 py-3 rounded hover:bg-blue-700 transition">Save Product</button>
            <a href="{{ route('products.index') }}" class="flex items-center text-gray-600 font-bold px-6 py-3 rounded hover:bg-gray-100 transition">Cancel</a>
        </div>
    </form>

    <!-- JavaScript for Dynamic Rows -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let rowCount = 1; // Array index tracker
            const container = document.getElementById('material-container');
            
            document.getElementById('add-material-btn').addEventListener('click', function() {
                const firstRow = container.querySelector('.material-row');
                const newRow = firstRow.cloneNode(true);
                
                // Update array indexes for Laravel
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
                        alert('A product must have at least one material in its recipe.');
                    }
                }
            });
        });
    </script>
@endsection