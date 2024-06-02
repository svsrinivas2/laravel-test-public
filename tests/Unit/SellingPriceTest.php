<?php

namespace Tests\Unit;

use App\Services\SalesService;
use PHPUnit\Framework\TestCase;

class SellingPriceTest extends TestCase
{
    private $salesService;

    /**
     * SetUp method to initialize the values
     */
    public function setUp(): void
    {
        $this->salesService = new SalesService();
    }

    /**
     * testValidInput
     *
     * @return void
     */
    public function testValidInput()
    {
        $quantity = 10;
        $unit_cost = 5.0;
        $profit_margin = 20.0;
        $shipping_cost = 2.5;

        $expectedSellingPrice = 10 * 5.0 / (1 - (20.0 / 100)) + 2.5;

        $result = $this->salesService->calculateSellingPrice($quantity, $unit_cost, $profit_margin, $shipping_cost);

        $this->assertEquals($expectedSellingPrice, $result);
    }

    /**
     * testZeroQuantity
     *
     * @return void
     */
    public function testZeroQuantity()
    {
        $quantity = 0;
        $unit_cost = 5.0;
        $profit_margin = 20.0;
        $shipping_cost = 2.5;

        $expectedSellingPrice = 2.5;

        $result = $this->salesService->calculateSellingPrice($quantity, $unit_cost, $profit_margin, $shipping_cost);

        $this->assertEquals($expectedSellingPrice, $result);
    }

    /**
     * testZeroUnitCost
     *
     * @return void
     */
    public function testZeroUnitCost()
    {
        $quantity = 10;
        $unit_cost = 0;
        $profit_margin = 20.0;
        $shipping_cost = 2.5;

        // Division by zero will result in INF (infinity)
        $expectedSellingPrice = 2.5;

        $result = $this->salesService->calculateSellingPrice($quantity, $unit_cost, $profit_margin, $shipping_cost);

        $this->assertEquals($expectedSellingPrice, $result);
    }

    /**
     * testNegativeProfitMargin
     *
     * @return void
     */
    public function testNegativeProfitMargin()
    {
        $quantity = 10;
        $unit_cost = 5.0;
        $profit_margin = -20.0;
        $shipping_cost = 2.5;

        // Negative profit margin should not affect the calculation
        $expectedSellingPrice = 10 * 5.0 / (1 - (-20.0 / 100)) + 2.5;

        $result = $this->salesService->calculateSellingPrice($quantity, $unit_cost, $profit_margin, $shipping_cost);

        $this->assertEquals($expectedSellingPrice, $result);
    }

    /**
     * testLargeQuantity
     *
     * @return void
     */
    public function testLargeQuantity()
    {
        $quantity = 1000000; // 1 million
        $unit_cost = 0.001; // $0.001
        $profit_margin = 10.0;
        $shipping_cost = 2.0;

        $expectedSellingPrice = 1000000 * 0.001 / (1 - (10.0 / 100)) + 2.0;

        $result = $this->salesService->calculateSellingPrice($quantity, $unit_cost, $profit_margin, $shipping_cost);

        $this->assertEquals($expectedSellingPrice, $result);
    }
}
