<?php

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;

uses(RefreshDatabase::class);

it('updates an existing product', function () {
    $product = Product::factory()->create();

    $response = $this->putJson(
        '/api/products/' . $product->id,
        [
            'name' => 'Updated Product Name',
            'price' => 20.99,
            'description' => 'Updated Description',
            'category' => 'Updated Category',
            'image' => 'http://example.com/updated.jpg',
        ]
    );

    $response->assertStatus(200);

    $this->assertDatabaseHas('products', [
        'name' => 'Updated Product Name',
        'price' => 20.99,
        'description' => 'Updated Description',
        'category' => 'Updated Category',
        'image_url' => 'http://example.com/updated.jpg',
    ]);
});

it('create a new product with not unique name', function () {
    $product = Product::factory()->create();

    $productSecond = Product::factory()->create();

    $response = $this->putJson(
        '/api/products/' . $product->id,
        ['name' => $productSecond->name]
    );

    $response->assertStatus(422)
             ->assertJsonStructure(['message']);
});

it('returns a status 404', function () {
    Log::spy();

    $product = Product::factory()->create();

    $response = $this->putJson('/api/products/'. $product->id + 1);

    $response->assertStatus(404)
             ->assertJsonStructure(['message']);

    Log::shouldHaveReceived('info')
        ->once();
});