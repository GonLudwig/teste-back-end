<?php

namespace App\Console\Commands\Products;

use App\Jobs\Product\CreateJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Import extends Command
{
    protected $signature = 'products:import {--id=* : External IDs of the products to import}';

    protected $description = 'Import products from an external API';

    protected $request = Http::baseUrl('https://fakestoreapi.com');

    public function handle(): void
    {
        $externalIds = $this->option('id');
        
        if (!empty($externalIds)) {
            foreach ($externalIds as $externalId) {
                $this->byId($externalId);
            }
        } else {
            $this->all();
        }
    }

    private function all(): void
    {
        try {
            $response = $this->request->get('products');
        } catch (\Exception $e) {
            Log::error('Console\Commands\Products\Import message: '.$e->getMessage(), [
                'exception' => $e,
            ]);

            $this->error('Error communicating with external API.');
        }

        $products = $response->json();

        foreach ($products as $productData) {
            CreateJob::dispatch($productData);
        }

        $this->info('All products have been imported successfully.');
    }

    private function byId(int $externalId): void
    {
        try {
            $response = $this->request->get('products/'.$externalId);
        } catch (\Exception $e) {
            Log::error('Console\Commands\Products\Import->byId message: '.$e->getMessage(), [
                'exception' => $e,
            ]);
            
            $this->error('Error communicating with external API.');
        }
        
        $productData = $response->json();

        if ($productData) {
            CreateJob::dispatch($productData);

            $this->info("Product ID: {$externalId} has been imported successfully.");
        } else {
            $this->error("Product ID: {$externalId} not found.");
        }
    }
}
