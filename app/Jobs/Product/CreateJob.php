<?php

namespace App\Jobs\Product;

use App\Models\Product;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class CreateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function handle(): void
    {
        try {
            Product::updateOrCreate(
                ['name' => Arr::get($this->data, 'title')],
                [
                    'price' => Arr::get($this->data, 'price'),
                    'description' => Arr::get($this->data, 'description'),
                    'category' => Arr::get($this->data, 'category'),
                    'image_url' => Arr::get($this->data, 'image')
                ]
            );
        } catch (Exception $e) {
            Log::error('Jobs\Product\CreateJob message: '.$e->getMessage(), [
                'exception' => $e,
            ]);
            throw $e;
        }
    }
}
