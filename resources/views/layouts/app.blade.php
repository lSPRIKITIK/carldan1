<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Production System - Carldan Metal Awards</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8 text-gray-800">
    
    <nav class="mb-8 flex gap-6 border-b pb-4">
        <h1 class="font-bold text-xl text-orange-600 mr-4">Carldan Production</h1>
        {{-- <a href="{{ route('dashboard.index') }}" class="text-blue-600 font-semibold hover:underline">Dashboard</a> --}}
        <a href="{{ route('materials.index') }}" class="text-blue-600 font-semibold hover:underline">Materials</a>
        <a href="{{ route('products.index') }}" class="text-blue-600 font-semibold hover:underline">Products</a>
        <a href="{{ route('orders.index') }}" class="text-blue-600 font-semibold hover:underline">Orders</a>
        <a href="{{ route('clients.index') }}" class="text-blue-600 font-semibold hover:underline">Clients</a>
        <a href="{{ route('payments.index') }}" class="text-blue-600 font-semibold hover:underline">Payments</a>
    </nav>

    <!-- System Notifications (Success / Error) -->
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 shadow-sm font-bold">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 shadow-sm font-bold">
            ⚠️ {{ session('error') }}
        </div>
    @endif

    <!-- Main Content Area -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        @yield('content')
    </div>
    
</body>
</html>