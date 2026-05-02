@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Materials Inventory</h2>
        <a href="{{ route('materials.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded transition shadow-sm">
            + Add New Material
        </a>
    </div>

    <div class="bg-white rounded border overflow-x-auto shadow-sm">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-100 text-gray-700 font-bold uppercase text-xs tracking-wider">
                <tr>
                    <th class="p-4 border-b w-16">ID</th>
                    <th class="p-4 border-b">Material Name</th>
                    <th class="p-4 border-b">Type</th>
                    <th class="p-4 border-b">Stock Level</th> <!-- Added missing header -->
                    <th class="p-4 border-b text-right">Unit Cost (PHP)</th>
                    <th class="p-4 border-b text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($materials as $material)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-4 text-gray-500">{{ $material->id }}</td>
                        <td class="p-4 font-bold text-gray-900">{{ $material->name }}</td>
                        <td class="p-4 text-gray-600">
                            <span class="bg-gray-100 px-2 py-1 rounded text-xs">{{ $material->type }}</span>
                        </td>
                        
                        <!-- Refined Stock Logic -->
                        <td class="p-4">
                            @php $stock = $material->stocks->last(); @endphp
                            @if($stock)
                                <div class="flex items-center">
                                    <span class="font-semibold {{ $stock->quantity < 10 ? 'text-red-600' : 'text-gray-800' }}">
                                        {{ $stock->quantity }} units
                                    </span>
                                    @if($stock->quantity < 10)
                                        <span class="ml-2 text-[10px] bg-red-100 text-red-600 px-2 py-0.5 rounded-full font-bold uppercase tracking-tighter">
                                            Low Stock
                                        </span>
                                    @endif
                                </div>
                            @else
                                <span class="text-gray-400 italic text-xs">No records</span>
                            @endif
                        </td>

                        <td class="p-4 text-right font-mono text-gray-900">
                            {{ number_format($material->unit_cost, 2) }}
                        </td>

                        <td class="p-4 text-center">
                            <div class="flex justify-center items-center gap-3">
                                <!-- Quick Restock Link (Optional: if you built the route) -->
                                <a href="#" class="text-green-600 hover:underline font-bold text-xs">Restock</a>
                                
                                <a href="{{ route('materials.edit', $material->id) }}" class="text-blue-500 hover:underline font-bold text-xs">Edit</a>
                                
                                <form action="{{ route('materials.destroy', $material->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this material? This will remove all associated stock records.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:underline font-bold text-xs bg-transparent border-none p-0">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-10 text-center text-gray-500 italic">
                            No materials found. Add some raw materials to begin tracking!
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection