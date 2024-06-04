<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository
{
    private Builder $product;

    public function __construct()
    {
        $this->product = Product::query();
    }

    public function index(array $querys): Collection
    {
        return $this->filterQuery($querys)->get();
    }

    public function show(int $id): ?Product
    {
        return $this->product->find($id);
    }

    public function store(array $data): Product
    {
        return $this->product->create($data);
    }

    public function update(Product $product, array $data): bool
    {
        return $product->update($data);
    }

    private function filterQuery(array $querys): Builder
    {
        foreach ($querys as $query => $queryValue) {
            switch ($query) {
                case 'name':
                    $this->product->where('name', $queryValue);
                    break;
                case 'category':
                    $this->product->where('category', $queryValue);
                    break;
                case 'image_url':
                    if ($queryValue == true) {
                        $this->product->whereNotNull('category');
                    } else {
                        $this->product->whereNull('category');
                    }
                    break;
            }
        }

        return $this->product;
    }
}