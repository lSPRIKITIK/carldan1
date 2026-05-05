@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-6">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Create New Order</h2>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 shadow-sm">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li class="text-sm font-bold">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('orders.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- 1. CLIENT SECTION -->
        <div class="bg-white p-6 rounded-xl border shadow-sm">
            <h3 class="font-bold text-lg text-gray-800 mb-4 border-b pb-2">1. Client Information</h3>
            
            <div class="mb-4">
                <label class="block font-bold text-sm text-gray-700 mb-1">Select Client</label>
                <select name="client_id" id="client_select" required onchange="toggleNewClientForm()" 
                        class="border p-2 rounded w-full focus:ring-2 focus:ring-blue-300 outline-none">
                    <option value="" disabled selected>Choose a client...</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}">
                            {{ $client->first_name }} {{ $client->last_name }}
                        </option>
                    @endforeach
                    <option value="new" class="font-bold text-blue-600 bg-blue-50">+ Add New Client</option>
                </select>
            </div>

            <div id="new_client_form" class="hidden p-4 bg-gray-50 border rounded-lg mt-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block font-semibold text-xs text-gray-600 mb-1">First Name</label>
                        <input type="text" name="client_fn" id="client_fn" class="border p-2 rounded w-full focus:ring-2 focus:ring-blue-300 outline-none">
                    </div>
                    <div>
                        <label class="block font-semibold text-xs text-gray-600 mb-1">Middle Name (Optional)</label>
                        <input type="text" name="client_mn" id="client_mn" class="border p-2 rounded w-full focus:ring-2 focus:ring-blue-300 outline-none">
                    </div>
                    <div>
                        <label class="block font-semibold text-xs text-gray-600 mb-1">Last Name</label>
                        <input type="text" name="client_ln" id="client_ln" class="border p-2 rounded w-full focus:ring-2 focus:ring-blue-300 outline-none">
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="block font-semibold text-xs text-gray-600 mb-1">Contact Number</label>
                    <input type="text" name="client_contact_number" id="client_contact_number" class="border p-2 rounded w-full focus:ring-2 focus:ring-blue-300 outline-none">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-semibold text-xs text-gray-600 mb-1">Street Address</label>
                        <input type="text" name="client_street" id="client_street" class="border p-2 rounded w-full focus:ring-2 focus:ring-blue-300 outline-none">
                    </div>
                    <div>
                        <label class="block font-semibold text-xs text-gray-600 mb-1">City</label>
                        <input type="text" name="client_city" id="client_city" class="border p-2 rounded w-full focus:ring-2 focus:ring-blue-300 outline-none">
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. ORDER DETAILS SECTION -->
        <div class="bg-white p-6 rounded-xl border shadow-sm">
            <h3 class="font-bold text-lg text-gray-800 mb-4 border-b pb-2">2. Order Details</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-sm text-gray-700 mb-1">Assigned Employee</label>
                    <select name="employee_id" required class="border p-2 rounded w-full focus:ring-2 focus:ring-blue-300 outline-none">
                        <option value="" disabled selected>Select an employee...</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}">
                                {{ $employee->first_name }} {{ $employee->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-sm text-gray-700 mb-1">Order Status</label>
                    <select name="order_status" required class="border p-2 rounded w-full focus:ring-2 focus:ring-blue-300 outline-none">
                        <option value="Pending">Pending</option>
                        <option value="In Production">In Production</option>
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-sm text-gray-700 mb-1">Order Date</label>
                    <input type="date" name="order_date" value="{{ date('Y-m-d') }}" required class="border p-2 rounded w-full focus:ring-2 focus:ring-blue-300 outline-none">
                </div>
                <div>
                    <label class="block font-bold text-sm text-gray-700 mb-1">Target Delivery Date</label>
                    <input type="date" name="delivery_date" required class="border p-2 rounded w-full focus:ring-2 focus:ring-blue-300 outline-none">
                </div>
            </div>
        </div>

        <!-- 3. PRODUCTS SECTION -->
        <div class="bg-white p-6 rounded-xl border shadow-sm">
            <h3 class="font-bold text-lg text-gray-800 mb-4 border-b pb-2">3. Products Ordered</h3>
            
            <div id="product-container" class="flex flex-col gap-3">
                <div class="product-row flex gap-2">
                    <select name="product_id[]" required class="border p-2 rounded w-2/3 focus:ring-2 focus:ring-blue-300 outline-none">
                        <option value="" disabled selected>Select a product...</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">
                                {{ $product->name }} (₱{{ number_format($product->price, 2) }})
                            </option>
                        @endforeach
                    </select>
                    <input type="number" name="quantity[]" min="1" placeholder="Qty" required class="border p-2 rounded w-1/3 focus:ring-2 focus:ring-blue-300 outline-none">
                    <button type="button" class="remove-btn bg-red-500 text-white px-3 rounded font-bold hover:bg-red-600 transition">X</button>
                </div>
            </div>

            <button type="button" id="add-product-btn" class="mt-4 bg-gray-200 text-gray-800 px-4 py-2 rounded font-bold hover:bg-gray-300 transition text-sm">
                + Add Another Product
            </button>
        </div>

        <!-- 4. DOWNPAYMENT SECTION -->
        <div class="bg-white p-6 rounded-xl border shadow-sm mb-6">
            <h3 class="font-bold text-lg text-gray-800 mb-4 border-b pb-2">4. Initial Downpayment (50%)</h3>
            
            <div class="bg-blue-50 border border-blue-200 p-4 rounded-lg mb-4 flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-600 font-bold">Grand Total: <span id="grand-total-display" class="text-gray-900">₱0.00</span></p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-blue-600 font-bold uppercase tracking-wide">Required 50% Downpayment</p>
                    <p id="downpayment-display" class="text-3xl font-black text-blue-700">₱0.00</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-sm text-gray-700 mb-1">Payment Method</label>
                    <select name="payment_method" id="payment_method_select" required onchange="toggleReferenceField()" class="border p-2 rounded w-full focus:ring-2 focus:ring-blue-300 outline-none">
                        <option value="Cash">Cash</option>
                        <option value="GCash">GCash</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                        <option value="Check">Check</option>
                    </select>
                </div>
                <div id="reference_container" class="hidden">
                    <label class="block font-bold text-sm text-gray-700 mb-1">Reference Number</label>
                    <input type="text" name="reference_number" id="reference_number_input" placeholder="e.g., GCash Ref No." class="border p-2 rounded w-full focus:ring-2 focus:ring-blue-300 outline-none">
                </div>
            </div>
        </div>

        <!-- BUTTONS -->
        <div class="flex gap-4">
            <button type="submit" class="bg-blue-600 text-white font-bold px-8 py-3 rounded hover:bg-blue-700 transition shadow-sm">Create Order</button>
            <a href="{{ route('orders.index') }}" class="flex items-center text-gray-600 font-bold px-6 py-3 rounded hover:bg-gray-100 transition">Cancel</a>
        </div>
    </form>
</div>

<!-- SCRIPTS -->
<script>
    // 1. Toggle Client Form
    function toggleNewClientForm() {
        const select = document.getElementById('client_select');
        const form = document.getElementById('new_client_form');
        
        const fields = ['client_fn', 'client_ln', 'client_contact_number', 'client_street', 'client_city'];

        if (select.value === 'new') {
            form.classList.remove('hidden');
            fields.forEach(id => document.getElementById(id).required = true);
        } else {
            form.classList.add('hidden');
            fields.forEach(id => {
                const el = document.getElementById(id);
                if(el) {
                    el.required = false;
                    el.value = ''; 
                }
            });
        }
    }

    // Toggle Reference Number Field
    function toggleReferenceField() {
        const method = document.getElementById('payment_method_select').value;
        const refContainer = document.getElementById('reference_container');
        const refInput = document.getElementById('reference_number_input');
        
        if (method === 'Cash') {
            refContainer.classList.add('hidden');
            refInput.value = ''; // Clear value when hidden to prevent submitting stale data
        } else {
            refContainer.classList.remove('hidden');
        }
    }

    // 2. Dynamic Product Rows
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('product-container');
        
        document.getElementById('add-product-btn').addEventListener('click', function() {
            const firstRow = container.querySelector('.product-row');
            const newRow = firstRow.cloneNode(true);
            
            newRow.querySelector('select').name = 'product_id[]';
            newRow.querySelector('select').value = ''; 
            
            newRow.querySelector('input').name = 'quantity[]';
            newRow.querySelector('input').value = '';
            
            container.appendChild(newRow);
        });

        container.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-btn')) {
                if (container.querySelectorAll('.product-row').length > 1) {
                    e.target.closest('.product-row').remove();
                    calculateTotals(); // Recalculate if a row is removed
                } else {
                    alert('An order must contain at least one product.');
                }
            }
        });
    });

    // 3. Live Downpayment Calculator
    function calculateTotals() {
        let grandTotal = 0;
        const rows = document.querySelectorAll('.product-row');
        
        rows.forEach(row => {
            const select = row.querySelector('select');
            const qtyInput = row.querySelector('input[type="number"]');
            
            if (select.value && qtyInput.value) {
                const selectedText = select.options[select.selectedIndex].text;
                const priceMatch = selectedText.match(/₱([\d,.]+)/); 
                
                if (priceMatch) {
                    const price = parseFloat(priceMatch[1].replace(/,/g, ''));
                    const qty = parseInt(qtyInput.value) || 0;
                    grandTotal += (price * qty);
                }
            }
        });

        const downpayment = grandTotal * 0.50;

        document.getElementById('grand-total-display').innerText = '₱' + grandTotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        document.getElementById('downpayment-display').innerText = '₱' + downpayment.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    }

    document.getElementById('product-container').addEventListener('change', calculateTotals);
    document.getElementById('product-container').addEventListener('input', calculateTotals);
</script>
@endsection