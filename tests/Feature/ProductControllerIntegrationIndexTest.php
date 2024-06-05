<?php

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns a JSON response with products', function () {
    Product::factory(5)->create();

    $response = $this->getJson('/api/products');

    $response->assertStatus(200)
             ->assertJsonStructure([
                 '*' => ['id', 'name', 'price'],
             ]);
});

it('returns a JSON response with products and query params', function () {
    $products = Product::factory(5)->create();
    $product = $products->first();
    $response = $this->getJson(
        "/api/products?name=$product->name&category=$product->category&image_url=true"
    );

    $response->assertStatus(200)
             ->assertJsonStructure([
                ['id', 'name', 'price']
             ]);
});
