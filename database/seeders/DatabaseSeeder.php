<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Supplier;
use App\Models\Item;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user (password: demo123)
        User::create([
            'name' => 'Admin User',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('demo123'),
            'full_name' => 'Admin User',
            'role' => 'admin',
        ]);

        // Create demo suppliers
        $suppliers = [
            [
                'name' => 'N/A',
                'contact_person' => 'Unknown',
                'phone' => 'N/A',
                'email' => 'N/A',
                'address' => 'N/A',
            ],
            [
                'name' => 'ABC Supplier',
                'contact_person' => 'John Doe',
                'phone' => '555-1234',
                'email' => 'john@abc.com',
                'address' => '123 Main St',
            ],
            [
                'name' => 'XYZ Trading',
                'contact_person' => 'Jane Smith',
                'phone' => '555-5678',
                'email' => 'jane@xyz.com',
                'address' => '456 Park Ave',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }

        // Create demo items
        $items = [
            [
                'name' => 'Sample Item 1',
                'supplier_id' => 2,
                'capital_price' => 80.00,
                'sell_price' => 100.00,
                'quantity_kilo' => 50.0,
                'image_path' => null,
            ],
            [
                'name' => 'Sample Item 2',
                'supplier_id' => 3,
                'capital_price' => 120.00,
                'sell_price' => 150.00,
                'quantity_kilo' => 75.0,
                'image_path' => null,
            ],
            [
                'name' => 'Sample Item 3',
                'supplier_id' => 2,
                'capital_price' => 170.00,
                'sell_price' => 200.00,
                'quantity_kilo' => 100.0,
                'image_path' => null,
            ],
        ];

        foreach ($items as $item) {
            Item::create($item);
        }
    }
}
