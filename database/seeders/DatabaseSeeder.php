<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Material;
use App\Models\Client;
use App\Models\Employee;
use App\Models\Product;
use App\Models\Order;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ---------------------------------------------------
        // 1. SEED MATERIALS (Matching V2 column names)
        // ---------------------------------------------------
        Material::create([
            'name' => 'Mahogany Wood Base', 
            'type' => 'Wood',
            'unit_cost' => 150.00
        ]);

        Material::create([
            'name' => 'Clear Acrylic Sheet', 
            'type' => 'Plastic',
            'unit_cost' => 250.00
        ]);

        Material::create([
            'name' => 'Gold Engraving Plate', 
            'type' => 'Metal',
            'unit_cost' => 85.00
        ]);

        Material::create([
            'name' => 'Mounting Screws', 
            'type' => 'Hardware',
            'unit_cost' => 2.50
        ]);

        // ---------------------------------------------------
        // 2. SEED CLIENTS (Matching V2 column names)
        // ---------------------------------------------------
        Client::create([
            'first_name' => 'Juan',
            'middle_name' => 'Santos',
            'last_name' => 'Dela Cruz',
            'contact_number' => '09171234567',
            'address' => 'Roxas Ave, Davao City'
        ]);
        
        Client::create([
            'first_name' => 'Maria',
            'middle_name' => null, // Middle name is nullable in our new database!
            'last_name' => 'Clara',
            'contact_number' => '09181234567',
            'address' => 'Bolton St, Davao City'
        ]);

        Client::create([
            'first_name' => 'John Kyle',
            'middle_name' => null,
            'last_name' => 'Banico',
            'contact_number' => '09199998888',
            'address' => 'Matina, Davao City'
        ]);
        // ---------------------------------------------------
        // 3. SEED EMPLOYEES (Needed to handle orders)
        // ---------------------------------------------------
        $dexter = Employee::create([
            'first_name' => 'Dexter',
            'middle_name' => 'B.',
            'last_name' => 'Aquino'
        ]);

        // ---------------------------------------------------
        // 4. SEED PRODUCTS (With their Bill of Materials)
        // ---------------------------------------------------
        $plaque = Product::create([
            'name' => 'Premium Wood Plaque',
            'type' => 'Plaque',
            'price' => 1500.00
        ]);

        // Attach materials to the product (Recipe: 1 Wood, 1 Gold Plate, 2 Screws)
        // Note: Assuming Wood is ID 1, Gold Plate is ID 3, Screws is ID 4 from our earlier seeder
        $plaque->materials()->sync([
            1 => ['required_quantity' => 1],
            3 => ['required_quantity' => 1],
            4 => ['required_quantity' => 2],
        ]);

        // ---------------------------------------------------
        // 5. SEED AN ORDER (The Shopping Cart)
        // ---------------------------------------------------
        $order = Order::create([
            'client_id' => 1, // Juan Dela Cruz
            'employee_id' => $dexter->id,
            'order_date' => now(),
            'status' => 'Pending'
        ]);

        // Attach the product to the order (Locking in the historical price)
        $order->products()->attach($plaque->id, [
            'quantity' => 5,
            'price' => $plaque->price
        ]);
    }
}