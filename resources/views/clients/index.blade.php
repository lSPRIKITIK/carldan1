@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Client Directory</h2>
        <a href="{{ route('clients.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded transition">
            Add New Client
        </a>
    </div>

    <div class="bg-white rounded border overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-200 text-gray-700 font-bold">
                <tr>
                    <th class="p-3 border-b">ID</th>
                    <th class="p-3 border-b">Full Name</th>
                    <th class="p-3 border-b">Contact Number</th>
                    <th class="p-3 border-b">Address</th>
                    <th class="p-3 border-b text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clients as $client)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-3 border-b text-gray-500">{{ $client->id }}</td>
                        <td class="p-3 border-b font-bold text-gray-900">{{ $client->first_name }} {{ $client->last_name }}</td>
                        <td class="p-3 border-b text-gray-600">{{ $client->contact_number }}</td>
                        <td class="p-3 border-b text-gray-600">{{ $client->address }}</td>
                        <td class="p-3 border-b text-center">
                            <a href="{{ route('clients.edit', $client->id) }}" class="text-blue-500 hover:underline mr-3">Edit</a>
                            <form action="{{ route('clients.destroy', $client->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this client? This action cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline cursor-pointer font-bold bg-transparent border-none p-0">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-6 border-b text-center text-gray-500">No clients found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection