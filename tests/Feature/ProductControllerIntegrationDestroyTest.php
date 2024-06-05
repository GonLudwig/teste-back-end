<?php

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('deletes an existing product', function () {
    $product = Product::factory()->create();

    $response = $this->deleteJson('/api/products/'.$product->id);

    $response->assertStatus(200);

    $this->assertDatabaseMissing('products', ['id' => $product->id]);
});

it('not found The product that has already been deleted', function () {

    $product = Product::factory()->create();
    $product->delete();

    $response = $this->deleteJson('/api/products/'.$product->id);

    $response->assertStatus(404)
        ->assertJsonStructure(['message']);
});
