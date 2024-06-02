<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Services\SalesService;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SalesController extends Controller
{
    protected $salesService;

    public function __construct()
    {
        $this->salesService = new SalesService();
    }

    /**
     * Function to getSales
     */
    public function getSales(): View
    {
        try {
            $productDetails = $this->salesService->getCoffeeProductDetails('Gold Coffee');
            $sales = $this->salesService->getSales('singleproductsale');

            return view('coffee_sales', ['product_details' => $productDetails[0], 'sales' => $sales]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     * Function to get MultiProductSales
     */
    public function getMultiProductSales(): View
    {
        try {
            $productDetails = $this->salesService->getCoffeeProductDetails('allproducts');
            $sales = $this->salesService->getSales('multiProduct');

            return view('coffee_multi_sales', ['products' => $productDetails, 'sales' => $sales]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     * Function to create single Product sale
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'quantity' => 'required|integer',
            'unit_cost' => 'required|numeric',
        ]);
        $this->salesService->createSale($request);

        return redirect()->route('coffee.sales')->with('success', 'Sale created successfully!');
    }

    /**
     * Function to create multi product sale
     */
    public function multiStore(Request $request): RedirectResponse
    {
        $request->validate([
            'quantity' => 'required|integer',
            'unit_cost' => 'required|numeric',
            'product' => 'required',
        ]);
        $this->salesService->createSale($request);

        return redirect()->route('coffee.multisales')->with('success', 'Sale created successfully!');
    }

    /**
     * Function to getProductDetails
     *
     * @param  mixed  $request
     * @return void
     */
    public function getProductDetails(Request $request)
    {
        $selectedValue = $request['value'];
        $productDetails = $this->salesService->getProductDetails($selectedValue);

        return ['data' => $productDetails];
    }
}
