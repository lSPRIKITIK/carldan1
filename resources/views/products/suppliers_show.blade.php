@extends('layouts.app')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h2 class="text-2xl font-bold">Supplier Details</h2>
    <div>
        <a href="{{ route('suppliers.edit', $supplier->id) }}" class="bg-indigo-600 text-white px-3 py-1 rounded">Edit</a>
        <a href="{{ route('suppliers.index') }}" class="ml-2 text-gray-600">Back</a>
    </div>
</div>
<div class="bg-white p-6 rounded border shadow-sm">
    <p><strong>Name:</strong> {{ $supplier->supplier_name }}</p>
    <p><strong>Contact:</strong> {{ $supplier->supplier_contact }}</p>
    <p><strong>Street:</strong> {{ $supplier->supplier_street }}</p>
    <p><strong>City:</strong> {{ $supplier->supplier_city }}</p>
</div>
@endsection