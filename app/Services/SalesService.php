<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SalesService
{
    /**
     * createSale
     */
    public function createSale(Request $request): JsonResponse
    {
        $quantity = $request->input('quantity');
        $unit_cost = $request->input('unit_cost');
        $product_id = $request->input('product');

        // Fetch product data with default value if no product is specified
        $product = $product_id
            ? Product::where('id', $product_id)->select('id', 'profit_margin', 'shipping_cost')->first()
            : Product::where('name', 'Gold Coffee')->select('id', 'profit_margin', 'shipping_cost')->first();

        // Use product's profit margin and shipping cost if not provided in the request
        $profit_margin = $request->input('profit_margin', $product->profit_margin);
        $shipping_cost = $request->input('shipping_cost', $product->shipping_cost);

        // Calculate selling price
        $selling_price = $this->calculateSellingPrice($quantity, $unit_cost, $profit_margin, $shipping_cost);

        // Create and save the sale
        $sale = new Sale();
        $sale->quantity = $quantity;
        $sale->unit_cost = $unit_cost;
        $sale->product_id = $product->id;
        $sale->selling_price = $selling_price;
        $sale->save();

        return response()->json(['success' => true, 'sale' => $sale]);
    }

    /**
     * Function get Sales records
     *
     * @param  Single  $singleOrMulti
     */
    public function getSales(string $singleOrMulti): Sale|Collection
    {
        return ($singleOrMulti === 'multiProduct') ? Sale::with('product')->get() : Sale::all();
    }

    /**
     * Function to getCoffeeProductDetails
     *
     * @param  mixed  $coffeeproduct
     * @return void
     */
    public function getCoffeeProductDetails(string $coffeeproduct)
    {
        if ($coffeeproduct === 'allproducts') {
            return Product::all();
        } else {
            return Product::where('name', $coffeeproduct)->get()->toArray();
        }
    }

    /**
     * calculateSellingPrice
     */
    public function calculateSellingPrice(int $quantity, float $unit_cost, float $profit_margin, float $shipping_cost): float
    {
        $cost = $quantity * $unit_cost;
        $profit_margin = $profit_margin / 100;

        return ($cost / (1 - $profit_margin)) + $shipping_cost;
    }

    /**
     * Function to getProductDetails with product id
     */
    public function getProductDetails(int $productId): Product
    {
        return Product::find($productId);
    }
}
