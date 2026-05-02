@extends('layouts.app')

@section('content')
    <h2 class="text-2xl font-bold mb-6">Add New Client</h2>

    <form action="{{ route('clients.store') }}" method="POST" class="max-w-lg bg-white p-6 rounded border">
        @csrf
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block font-bold mb-1 text-gray-700">First Name</label>
                <input type="text" name="first_name" required class="w-full border p-2 rounded">
            </div>
            <div>
                <label class="block font-bold mb-1 text-gray-700">Last Name</label>
                <input type="text" name="last_name" required class="w-full border p-2 rounded">
            </div>
        </div>
        <div class="mb-4">
            <label class="block font-bold mb-1 text-gray-700">Contact Number</label>
            <input type="text" name="contact_number" required class="w-full border p-2 rounded">
        </div>
        <div class="mb-6">
            <label class="block font-bold mb-1 text-gray-700">Address</label>
            <textarea name="address" required class="w-full border p-2 rounded"></textarea>
        </div>
        <button type="submit" class="bg-blue-600 text-white font-bold px-6 py-2 rounded hover:bg-blue-700">Save Client</button>
    </form>
@endsection