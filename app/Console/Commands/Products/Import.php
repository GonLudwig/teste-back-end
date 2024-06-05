<?php

namespace App\Console\Commands\Products;

use App\Jobs\Product\CreateJob;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Import extends Command
{
    protected $signature = 'products:import {--id=* : External IDs of the products to import}';

    protected $description = 'Import products from an external API';

    protected $request;

    public function __construct()
    {
        parent::__construct();
        $this->request = Http::baseUrl(\rtrim(\env('COMMAND_PRODUCT_IMPORT_URL'), '/'));
    }

    public function handle(): void
    {
        $externalIds = $this->option('id');

        if (! empty($externalIds)) {
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
        } catch (Exception $e) {
            Log::error('Console\Commands\Products\Import->all message: '.$e->getMessage(), [
                'exception' => $e,
            ]);

            $this->error('Error communicating with external API.');
            exit(1);
        }

        if ($response->status() != 200) {
            Log::error('Console\Commands\Products\Import->all ', [
                'response' => $response,
            ]);

            $this->error('Error communicating with external API.');
            exit(1);
        }

        foreach ($response->collect() as $productData) {
            CreateJob::dispatch($productData);
        }

        $this->info('All products have been imported successfully.');
    }

    private function byId(int $externalId): void
    {
        try {
            $response = $this->request->get('products/'.$externalId);
        } catch (Exception $e) {
            Log::error('Console\Commands\Products\Import->byId message: '.$e->getMessage(),
                ['exception' => $e]
            );

            $this->error('Error communicating with external API.');
            exit(1);
        }

        if ($response->status() != 200) {
            Log::error('Console\Commands\Products\Import->all ', ['response' => $response]);

            $this->error('Error communicating with external API.');
            exit(1);
        }

        $productData = $response->collect();

        if ($productData->isNotEmpty()) {
            CreateJob::dispatch($productData->toArray());

            $this->info("Product ID: {$externalId} has been imported successfully.");
        } else {
            $this->error("Product ID: {$externalId} not found.");
        }
    }
}
