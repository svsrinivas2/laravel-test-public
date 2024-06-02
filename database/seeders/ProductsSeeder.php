<?php

namespace Database\Seeders;

use App\Models\Product;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            $productsArray = [
                ['name' => 'Gold Coffee', 'profit_margin' => 25, 'shipping_cost' => 10],
                ['name' => 'Arabic Coffee', 'profit_margin' => 15, 'shipping_cost' => 10],
            ];
            foreach ($productsArray as $product) {
                Product::updateOrCreate(
                    [
                        'name' => $product['name'],
                    ],
                    [
                        'profit_margin' => $product['profit_margin'],
                        'shipping_cost' => $product['shipping_cost'],
                    ]
                );
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }

    }
}
