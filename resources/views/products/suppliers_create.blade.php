@extends('layouts.app')

@section('content')
<h2 class="text-2xl font-bold mb-4">Create Supplier</h2>
<form action="{{ route('suppliers.store') }}" method="POST" class="space-y-4 bg-white p-6 rounded border shadow-sm">
    @csrf
    <div>
        <label class="block text-sm font-medium text-gray-700">Name</label>
        <input type="text" name="supplier_name" value="{{ old('supplier_name') }}" required class="mt-1 block w-full border p-2 rounded" />
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Contact</label>
        <input type="text" name="supplier_contact" value="{{ old('supplier_contact') }}" required class="mt-1 block w-full border p-2 rounded" />
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Street</label>
        <input type="text" name="supplier_street" value="{{ old('supplier_street') }}" class="mt-1 block w-full border p-2 rounded" />
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">City</label>
        <input type="text" name="supplier_city" value="{{ old('supplier_city') }}" class="mt-1 block w-full border p-2 rounded" />
    </div>
    <div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Save Supplier</button>
        <a href="{{ route('suppliers.index') }}" class="ml-4 text-gray-600">Cancel</a>
    </div>
</form>
@endsection