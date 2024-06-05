<?php

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;

uses(RefreshDatabase::class);

it('returns a JSON response with the product', function () {
    $product = Product::factory()->create();

    $response = $this->getJson('/api/products/' . $product->id);

    $response->assertStatus(200)
             ->assertJson($product->toArray());
});

it('returns a status 400 parameter does not correspond to an ID', function () {
    Log::spy();

    $response = $this->getJson('/api/products/error');

    $response->assertStatus(400)
             ->assertJsonStructure(['message']);

    Log::shouldHaveReceived('info')
        ->once();
});

it('returns a status 404', function () {
    Log::spy();

    $product = Product::factory()->create();

    $response = $this->getJson('/api/products/'. $product->id + 1);

    $response->assertStatus(404)
             ->assertJsonStructure(['message']);

    Log::shouldHaveReceived('info')
        ->once();
});