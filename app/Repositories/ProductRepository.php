<?php

namespace App\Repositories;

use App\Models\Product;
use ErrorException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductRepository
{
    private Builder $product;

    public function __construct()
    {
        $this->product = Product::query();
    }

    public function index(array $querys = []): Collection
    {
        return $this->filterQuery($querys)->get();
    }

    public function show(int $id): ?Product
    {
        return $this->product->find($id);
    }

    public function store(array $data): Product
    {
        try {
            DB::beginTransaction();
            return $this->product->create($data);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $code = strval(\time());
            Log::error('ProductRepository->store code: '.$code.' message: '.$e->getMessage().' data: ',$data);
            throw new ErrorException('Unexpected error. code: '.$code);
        }
    }

    public function update(Product $product, array $data): bool
    {
        try {
            DB::beginTransaction();
            return $product->update($data);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $code = strval(\time());
            Log::error('ProductRepository->update code: '.$code.' message: '.$e->getMessage().' data: ',$data);
            throw new ErrorException('Unexpected error. code: '.$code);
        }
    }

    public function destroy(Product $product): bool
    {
        try {
            DB::beginTransaction();
            return $product->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $code = strval(\time());
            Log::error('ProductRepository->destroy code: '.$code.' message: '.$e->getMessage());
            throw new ErrorException('Unexpected error. code: '.$code);
        }
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
                    if ($queryValue) {
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