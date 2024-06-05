<?php

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('create a new product', function () {
    $productData = [
        'name' => 'Test Product',
        'price' => 10.99,
        'description' => 'Test Description',
        'category' => 'Test Category',
        'image' => 'http://example.com/test.jpg',
    ];

    $response = $this->postJson('/api/products', $productData);

    $response->assertStatus(201);

    $this->assertDatabaseHas('products', [
        'name' => 'Test Product',
        'price' => 10.99,
        'description' => 'Test Description',
        'category' => 'Test Category',
        'image_url' => 'http://example.com/test.jpg',
    ]);
});

it('create a new product with not unique name', function () {
    $productData = [
        'name' => Product::factory()->create()->name,
        'price' => 10.99,
        'description' => 'Test Description',
        'category' => 'Test Category',
        'image' => 'http://example.com/test.jpg',
    ];

    $response = $this->postJson('/api/products', $productData);

    $response->assertStatus(422)
             ->assertJsonStructure(['message']);
});

it('create a new product with not numeric price', function () {

    $productData = [
        'name' => 'Test Product',
        'price' => 'teste',
        'description' => 'Test Description',
        'category' => 'Test Category',
        'image' => 'http://example.com/test.jpg',
    ];

    $response = $this->postJson('/api/products', $productData);

    $response->assertStatus(422)
             ->assertJsonStructure(['message']);

});