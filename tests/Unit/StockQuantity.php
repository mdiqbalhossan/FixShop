<?php

namespace Tests\Unit;

use App\Models\Adjustment;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Transfer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class StockQuantity extends TestCase
{
    use RefreshDatabase;
    
    public function test_stock_quantity_calculation()
    {
        $product_id = 1;
        $warehouse_id = 1;

        $this->createTestData($product_id, $warehouse_id);

        $result = stock_quantity($product_id, $warehouse_id);

        $this->assertEquals(15, $result);
    }

    protected function createTestData($product_id, $warehouse_id)
    {
        Adjustment::create([
            'warehouse_id' => $warehouse_id,
            'products' => [
                ['product_id' => $product_id, 'type' => 'add', 'quantity' => 10],
                ['product_id' => $product_id, 'type' => 'subtract', 'quantity' => 5],
            ],
        ]);

        Sale::create([
            'warehouse_id' => $warehouse_id,
            'products' => [
                ['product_id' => $product_id, 'quantity' => 20],
            ],
        ]);

        Sale::create([
            'warehouse_id' => $warehouse_id,
            'returns' => [
                ['product_id' => $product_id, 'quantity' => 5],
            ],
        ]);

        Purchase::create([
            'warehouse_id' => $warehouse_id,
            'products' => [
                ['product_id' => $product_id, 'quantity' => 30],
            ],
        ]);

        Purchase::create([
            'warehouse_id' => $warehouse_id,
            'returns' => [
                ['product_id' => $product_id, 'return_quantity' => 5],
            ],
        ]);

        Transfer::create([
            'to_warehouse_id' => $warehouse_id,
            'products' => [
                ['product_id' => $product_id, 'quantity' => 10],
            ],
        ]);

        Transfer::create([
            'from_warehouse_id' => $warehouse_id,
            'products' => [
                ['product_id' => $product_id, 'quantity' => 10],
            ],
        ]);
    }
}
