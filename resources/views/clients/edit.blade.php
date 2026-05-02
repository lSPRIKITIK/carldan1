@extends('layouts.app')

@section('content')
    <h2 class="text-2xl font-bold mb-6">Edit Client: {{ $client->first_name }} {{ $client->last_name }}</h2>

    <form action="{{ route('clients.update', $client->id) }}" method="POST" class="max-w-lg bg-white p-6 rounded border">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block font-bold mb-1 text-gray-700">First Name</label>
                <input type="text" name="first_name" value="{{ $client->first_name }}" required class="w-full border p-2 rounded">
            </div>
            <div>
                <label class="block font-bold mb-1 text-gray-700">Last Name</label>
                <input type="text" name="last_name" value="{{ $client->last_name }}" required class="w-full border p-2 rounded">
            </div>
        </div>
        <div class="mb-4">
            <label class="block font-bold mb-1 text-gray-700">Contact Number</label>
            <input type="text" name="contact_number" value="{{ $client->contact_number }}" required class="w-full border p-2 rounded">
        </div>
        <div class="mb-6">
            <label class="block font-bold mb-1 text-gray-700">Address</label>
            <textarea name="address" required class="w-full border p-2 rounded">{{ $client->address }}</textarea>
        </div>
        
        <div class="flex gap-4 mt-6">
            <button type="submit" class="bg-blue-600 text-white font-bold px-6 py-2 rounded hover:bg-blue-700 transition">Update Client</button>
            <a href="{{ route('clients.index') }}" class="flex items-center text-gray-600 font-bold px-6 py-2 rounded hover:bg-gray-100 transition">Cancel</a>
        </div>
    </form>
@endsection