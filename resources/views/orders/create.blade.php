@extends('layouts.app')

@section('content')
    <h2 class="text-2xl font-bold mb-6">Create New Order (V2)</h2>

    <form action="{{ route('orders.store') }}" method="POST" class="max-w-2xl">
        @csrf

        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block font-bold mb-1">Select Client</label>
                <select name="client_id" required class="w-full border p-2 rounded">
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->first_name }} {{ $client->last_name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block font-bold mb-1">Handled By Employee</label>
                <select name="employee_id" required class="w-full border p-2 rounded">
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}">{{ $employee->first_name }} {{ $employee->last_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block font-bold mb-1">Order Date</label>
                <input type="date" name="order_date" value="{{ date('Y-m-d') }}" required class="w-full border p-2 rounded">
            </div>
            <div>
                <label class="block font-bold mb-1">Target Delivery Date</label>
                <input type="date" name="delivery_date" class="w-full border p-2 rounded">
            </div>
        </div>

        <!-- The Shopping Cart Section -->
        <div class="mb-6 border p-4 rounded bg-gray-50">
            <h3 class="font-bold mb-3 text-lg">Order Items</h3>
            
            <div id="product-container" class="flex flex-col gap-3">
                <!-- Initial Product Row. Note the array naming: products[0][product_id] -->
                <div class="product-row flex gap-2">
                    <select name="products[0][product_id]" required class="w-2/3 border p-2 rounded">
                        <option value="" disabled selected>Select a Product...</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }} (PHP {{ $product->price }})</option>
                        @endforeach
                    </select>
                    <input type="number" name="products[0][quantity]" placeholder="Qty" required min="1" class="w-1/3 border p-2 rounded">
                    <button type="button" class="remove-btn bg-red-500 text-white px-3 rounded font-bold hover:bg-red-600">X</button>
                </div>
            </div>

            <button type="button" id="add-product-btn" class="mt-4 bg-gray-200 text-gray-800 px-4 py-2 rounded font-bold hover:bg-gray-300">
                + Add Another Item
            </button>
        </div>

        <button type="submit" class="bg-blue-600 text-white font-bold px-6 py-3 rounded hover:bg-blue-700">Submit Order & Deduct Inventory</button>
    </form>

    <!-- JavaScript to handle dynamic rows and array indexes -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let rowCount = 1; // Keep track so array indexes are unique
            const container = document.getElementById('product-container');
            
            document.getElementById('add-product-btn').addEventListener('click', function() {
                const firstRow = container.querySelector('.product-row');
                const newRow = firstRow.cloneNode(true);
                
                // Update the array indexes for Laravel (e.g., products[1][product_id])
                newRow.querySelector('select').name = `products[${rowCount}][product_id]`;
                newRow.querySelector('select').value = ''; 
                
                newRow.querySelector('input').name = `products[${rowCount}][quantity]`;
                newRow.querySelector('input').value = '';
                
                container.appendChild(newRow);
                rowCount++;
            });

            container.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-btn')) {
                    if (container.querySelectorAll('.product-row').length > 1) {
                        e.target.closest('.product-row').remove();
                    } else {
                        alert('An order must contain at least one item.');
                    }
                }
            });
        });
    </script>
@endsection