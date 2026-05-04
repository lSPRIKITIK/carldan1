@extends('layouts.app')

@section('content')
<div class="container-fluid py-4" style="background-color: #f8fafc; min-height: 100vh;">
    
    <div class="card border-0 shadow-sm rounded-3 p-4 mx-auto" style="background-color: #ffffff; max-width: 600px;">
        
        <div class="mb-4">
            <h4 class="fw-bold mb-1" style="color: #1a202c;">Process Final Payment</h4>
            <p class="text-muted small">Record the remaining balance for this order.</p>
        </div>

        <form action="{{ route('payments.store') }}" method="POST">
            @csrf 

            <input type="hidden" name="order_id" value="{{ $order->id }}">

            <div class="mb-3">
                <label class="form-label fw-semibold text-muted small text-uppercase block mb-1">Order Details</label>
                <input type="text" class="form-control bg-gray-50 border p-2 rounded w-full" value="PO-{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }} - {{ $order->client->first_name ?? '' }} {{ $order->client->last_name ?? '' }}" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold text-muted small text-uppercase block mb-1">Processed By (Employee)</label>
                <select name="employee_id" class="form-select border p-2 rounded w-full" required>
                    <option value="" disabled selected>Select your name...</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}">
                            {{ $employee->first_name }} {{ $employee->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold text-muted small text-uppercase block mb-1">Payment Method</label>
                <select name="payment_method" id="payment_method" class="form-select border p-2 rounded w-full" required onchange="toggleReferenceField()">
                    <option value="" disabled selected>Select Payment Method...</option>
                    <option value="Cash">Cash</option>
                    <option value="GCash">GCash</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                </select>
            </div>

            <div class="mb-3" id="reference_container" style="display: none;">
                <label class="form-label fw-semibold text-muted small text-uppercase block mb-1">Reference Number</label>
                <input type="text" name="reference_number" id="reference_number" class="form-control border p-2 rounded w-full" placeholder="e.g., 8123 4567 8901">
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold text-muted small text-uppercase block mb-1">Payment Date</label>
                <input type="date" name="payment_date" class="form-control border p-2 rounded w-full" value="{{ date('Y-m-d') }}" required>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold text-muted small text-uppercase block mb-1">Due Balance</label>
                
                <!-- HIDDEN: Sends the raw number (e.g. 11715.75) to the database safely -->
                <input type="hidden" name="amount" value="{{ $remainingBalance }}">
                
                <!-- VISIBLE: Shows the beautiful formatted string to the user -->
                <input type="text" class="form-control bg-gray-50 border p-2 rounded w-full text-600 font-bold text-lg tracking-wide" 
                       value="₱ {{ number_format($remainingBalance, 2) }}" readonly>
            </div>

            <div class="flex justify-end gap-2 mt-4">
                <a href="{{ route('payments.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300 transition">Cancel</a>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition font-semibold">Confirm Payment</button>
            </div>
        </form>

    </div>
</div>

<script>
    function toggleReferenceField() {
        const method = document.getElementById('payment_method').value;
        const container = document.getElementById('reference_container');
        const input = document.getElementById('reference_number');

        if (method === 'GCash' || method === 'Bank Transfer') {
            container.style.display = 'block'; 
            input.required = true;            
        } else {
            container.style.display = 'none';  
            input.required = false;            
            input.value = '';                  
        }
    }
</script>
@endsection