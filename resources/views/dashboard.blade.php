@extends('layouts.app')

@section('content')
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Dashboard Overview</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Total Revenue Card -->
        <div class="bg-white p-6 rounded-xl border shadow-sm">
            <h3 class="font-bold text-lg text-gray-800 mb-2">Total Revenue</h3>
            <p class="text-4xl font-extrabold text-green-600">
                ₱{{ number_format($totalRevenue->total_revenue ?? 0, 2) }}
            </p>
        </div>

        <!-- Monthly Revenue Card -->
        <div class="bg-white p-6 rounded-xl border shadow-sm col-span-2">
            <h3 class="font-bold text-lg text-gray-800 mb-4">Monthly Revenue Breakdown</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-gray-100 text-gray-700 font-bold uppercase text-xs tracking-wider">
                        <tr>
                            <th class="p-3 border-b">Month</th>
                            <th class="p-3 border-b text-right">Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($monthlyRevenue as $revenue)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="p-3 border-b">{{ \Carbon\Carbon::createFromFormat('Y-m', $revenue->month)->format('F Y') }}</td>
                                <td class="p-3 border-b text-right">₱{{ number_format($revenue->monthly_revenue, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="p-3 text-center text-gray-500 italic">No monthly revenue data available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- You can add recent orders or other dashboard elements here -->
    <h3 class="font-bold text-lg text-gray-800 mb-4">Recent Orders (Last 5)</h3>
    {{-- Add a table or list to display $recentOrders here if desired --}}
    <p class="text-gray-600">Displaying recent orders is left as an exercise for you, but the data is available in the `$recentOrders` variable.</p>

@endsection
