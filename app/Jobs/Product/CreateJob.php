<?php

namespace App\Jobs\Product;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CreateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function handle(): void
    {
        try {
            Product::updateOrCreate(
                ['name' => $this->data['title']],
                [
                    'price' => $this->data['price'],
                    'description' => $this->data['description'],
                    'category' => $this->data['category'],
                    'image_url' => $this->data['image']
                ]
            );
        } catch (\Exception $e) {
            Log::error('Jobs\Product\CreateJob message: '.$e->getMessage(), [
                'exception' => $e,
            ]);
            throw $e;
        }
    }
}
