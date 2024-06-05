<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use App\Jobs\Product\CreateJob;

it('imports all products successfully', function () {
    Queue::fake();
    Http::fake([
        'https://fakestoreapi.com/products' => Http::response([
            [
                'id' => 1,
                'title' => 'Product 1',
                'price' => 10.00,
                'description' => 'Description 1',
                'category' => 'Category 1',
                'image' => 'https://example.com/image1.jpg',
            ],
            [
                'id' => 2,
                'title' => 'Product 2',
                'price' => 20.00,
                'description' => 'Description 2',
                'category' => 'Category 2',
                'image' => 'https://example.com/image2.jpg',
            ],
        ]),
    ]);

    $this->artisan('products:import')
        ->expectsOutput('All products have been imported successfully.')
        ->assertSuccessful();

    Queue::assertPushed(CreateJob::class, 2);
});

it('imports a product by id successfully', function () {
    Queue::fake();

    Http::fake([
        'https://fakestoreapi.com/products/1' => Http::response([
            'id' => 1,
            'title' => 'Product 1',
            'price' => 10.00,
            'description' => 'Description 1',
            'category' => 'Category 1',
            'image' => 'https://example.com/image1.jpg',
        ]),
    ]);

    $this->artisan('products:import', ['--id' => [1]])
        ->expectsOutput("Product ID: 1 has been imported successfully.")
        ->assertSuccessful();

    Queue::assertPushed(CreateJob::class, function ($job) {
        return $job->data['title'] === 'Product 1';
    });
});

