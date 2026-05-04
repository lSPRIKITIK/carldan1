@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-6">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Restock Material</h2>

    <form action="{{ route('stocks.store') }}" method="POST" class="bg-white p-6 rounded-xl border shadow-sm">
        @csrf

        <!-- Hidden ID for the database -->
        <input type="hidden" name="material_id" value="{{ $material->id }}">

        <div class="mb-6">
            <label class="block font-bold text-sm text-gray-700 mb-1">Material to Restock</label>
            <input type="text" class="border p-2 rounded w-full bg-gray-50 text-gray-600 font-semibold cursor-not-allowed" value="{{ $material->name }}" readonly>
            <p class="text-[11px] text-gray-500 mt-1">Current Stock: {{ $material->stocks->sum('quantity') ?? 0 }} units</p>
        </div>

        <!-- Supplier Dropdown -->
        <div class="mb-4">
            <label class="block font-bold text-sm text-gray-700 mb-1">Select Supplier</label>
            <select name="supplier_id" id="supplier_select" required onchange="toggleNewSupplierForm()" 
                    class="border p-2 rounded w-full focus:ring-2 focus:ring-blue-300 outline-none">
                <option value="" disabled selected>Choose a supplier...</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}">{{ $supplier->supplier_name }}</option>
                @endforeach
                <option value="new" class="font-bold text-blue-600 bg-blue-50">+ Add New Supplier</option>
            </select>
        </div>

        <!-- HIDDEN BY DEFAULT: New Supplier Text Fields -->
        <div id="new_supplier_form" class="mb-6 p-5 bg-gray-50 border rounded-lg hidden">
            <h5 class="text-sm font-bold text-gray-700 mb-4 uppercase tracking-wide">New Supplier Details</h5>
            
            <div class="mb-4">
                <label class="block font-semibold text-xs text-gray-600 mb-1">Supplier Name</label>
                <input type="text" name="supplier_name" id="supplier_name" class="border p-2 rounded w-full focus:ring-2 focus:ring-blue-300 outline-none" placeholder="e.g. Ace Hardware">
            </div>

            <div class="mb-4">
                <label class="block font-semibold text-xs text-gray-600 mb-1">Contact Number</label>
                <input type="text" name="supplier_contact" id="supplier_contact" class="border p-2 rounded w-full focus:ring-2 focus:ring-blue-300 outline-none" placeholder="e.g. 0912 345 6789">
            </div>

            <!-- Atomic Address Fields side-by-side -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-2">
                <div>
                    <label class="block font-semibold text-xs text-gray-600 mb-1">Street / Barangay</label>
                    <input type="text" name="supplier_street" id="supplier_street" class="border p-2 rounded w-full focus:ring-2 focus:ring-blue-300 outline-none" placeholder="e.g. 123 San Pedro St.">
                </div>
                <div>
                    <label class="block font-semibold text-xs text-gray-600 mb-1">City</label>
                    <input type="text" name="supplier_city" id="supplier_city" class="border p-2 rounded w-full focus:ring-2 focus:ring-blue-300 outline-none" placeholder="e.g. Davao City">
                </div>
            </div>
        </div>

        <!-- NEW: Quantity and Unit Cost side-by-side -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8 mt-6">
            <div>
                <label class="block font-bold text-sm text-gray-700 mb-1">Quantity to Add</label>
                <input type="number" name="amount" min="1" required 
                       placeholder="Enter amount..." class="border p-2 rounded w-full focus:ring-2 focus:ring-green-300 outline-none font-bold text-green-700 text-lg">
            </div>
            <div>
                <label class="block font-bold text-sm text-gray-700 mb-1">Unit Cost (₱)</label>
                <!-- Automatically defaults to the current price so you don't have to guess -->
                <input type="number" name="unit_cost" step="0.01" min="0" required 
                       value="{{ $material->price }}" class="border p-2 rounded w-full focus:ring-2 focus:ring-blue-300 outline-none font-bold text-gray-700 text-lg">
            </div>
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="bg-green-600 text-white font-bold px-8 py-2.5 rounded hover:bg-green-700 transition shadow-sm">
                Confirm Restock
            </button>
            <a href="{{ route('materials.index') }}" class="text-sm font-bold text-gray-500 hover:text-gray-800 px-4 py-2">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
    function toggleNewSupplierForm() {
        const select = document.getElementById('supplier_select');
        const form = document.getElementById('new_supplier_form');
        
        const nameInput = document.getElementById('supplier_name');
        const contactInput = document.getElementById('supplier_contact');
        const streetInput = document.getElementById('supplier_street');
        const cityInput = document.getElementById('supplier_city');

        if (select.value === 'new') {
            form.classList.remove('hidden');
            nameInput.required = true;
            contactInput.required = true;
            streetInput.required = true;
            cityInput.required = true;
        } else {
            form.classList.add('hidden');
            nameInput.required = false;
            contactInput.required = false;
            streetInput.required = false;
            cityInput.required = false;
            
            nameInput.value = '';
            contactInput.value = '';
            streetInput.value = '';
            cityInput.value = '';
        }
    }
</script>
@endsection