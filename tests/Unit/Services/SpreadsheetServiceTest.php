<?php

namespace Tests\Unit\Services;

use App\Jobs\ProcessProductImage;
use App\Models\Product;
use App\Services\SpreadsheetService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class SpreadsheetServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $spreadsheetService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->spreadsheetService = new SpreadsheetService();
    }

    public function test_processSpreadsheet_successfully_creates_products()
    {
        Queue::fake();

        $filePath = 'path/to/fake/spreadsheet.xlsx';
        $fakeData = [
            ['product_code' => 'P001', 'quantity' => 10],
            ['product_code' => 'P002', 'quantity' => 5],
        ];

        // Mocking the importer to return fake data
        app()->instance('importer', new class($fakeData) {
            private $data;
            public function __construct($data)
            {
                $this->data = $data;
            }
            public function import($filePath)
            {
                return $this->data;
            }
        });

        $this->spreadsheetService->processSpreadsheet($filePath);

        $this->assertCount(2, Product::all());
        $this->assertDatabaseHas('products', ['code' => 'P001', 'quantity' => 10]);
        $this->assertDatabaseHas('products', ['code' => 'P002', 'quantity' => 5]);

        Queue::assertPushed(ProcessProductImage::class, 2);
    }

    public function test_processSpreadsheet_skips_invalid_rows()
    {
        Queue::fake();

        $filePath = 'path/to/fake/spreadsheet.xlsx';
        $fakeData = [
            ['product_code' => 'P001', 'quantity' => 10],
            ['product_code' => '', 'quantity' => 5], // Invalid row
            ['product_code' => 'P001', 'quantity' => 0], // Invalid row (duplicate)
        ];

        app()->instance('importer', new class($fakeData) {
            private $data;
            public function __construct($data)
            {
                $this->data = $data;
            }
            public function import($filePath)
            {
                return $this->data;
            }
        });

        $this->spreadsheetService->processSpreadsheet($filePath);

        $this->assertCount(1, Product::all()); // Only P001 should be created
        $this->assertDatabaseHas('products', ['code' => 'P001', 'quantity' => 10]);
        $this->assertDatabaseMissing('products', ['code' => '', 'quantity' => 5]);
        $this->assertDatabaseMissing('products', ['code' => 'P001', 'quantity' => 0]);

        Queue::assertPushed(ProcessProductImage::class, 1);
    }
}
