<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create baseline data
        \App\Models\Client::factory(50)->create();
        \App\Models\Supplier::factory(20)->create();
        \App\Models\Material::factory(30)->create();
        $products = \App\Models\Product::factory(20)->create();
        
        // Ensure we have at least one employee[cite: 4]
        $employee = \App\Models\Employee::first() ?? \App\Models\Employee::factory()->create();

        // 2. Create 50 Orders[cite: 7]
        \App\Models\Order::factory(50)->create([
            'employee_id' => $employee->id
        ])->each(function ($order) use ($products, $employee) {
            // Randomly pick 1 to 3 products for the order
            $items = $products->random(rand(1, 3));
            $totalOrderPrice = 0;

            foreach ($items as $product) {
                $qty = rand(1, 10);
                $priceAtTime = $product->price;
                $totalOrderPrice += ($qty * $priceAtTime);

                // Attach to your pivot table (assuming product_order)[cite: 1]
                $order->products()->attach($product->id, [
                    'quantity' => $qty,
                    'price'    => $priceAtTime
                ]);

                // Create Production record[cite: 9]
                \App\Models\Production::create([
                    'order_id'      => $order->id,
                    'product_id'    => $product->id,
                    'prod_status'   => $order->status === 'Completed' ? 'Completed' : 'Pending',
                    'prod_note'     => 'Auto-generated production task.',
                    'prod_start_date' => $order->order_date,
                ]);
            }

            // 3. SEED PAYMENTS
            
            // Always create the 50% Downpayment
            \App\Models\Payment::create([
                'order_id'         => $order->id,
                'employee_id'      => $employee->id,
                'payment_method'   => fake()->randomElement(['Cash', 'GCash', 'Bank Transfer']),
                'payment_date'     => $order->order_date,
                'amount'           => $totalOrderPrice / 2,
                'reference_number' => 'DOWNPAYMENT',
            ]);

            // If the order is "Completed", create the final 50% payment
            if ($order->status === 'Completed') {
                \App\Models\Payment::create([
                    'order_id'         => $order->id,
                    'employee_id'      => $employee->id,
                    'payment_method'   => fake()->randomElement(['Cash', 'GCash', 'Bank Transfer']),
                    'payment_date'     => $order->delivery_date ?? now(),
                    'amount'           => $totalOrderPrice / 2,
                    'reference_number' => 'SETTLEMENT_' . strtoupper(fake()->bothify('??###')),
                ]);
            }
        });
    }
}