<?php

namespace App\Repositories;

use App\Models\Product;
use ErrorException;
use Exception;
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

    public function all(array $querys = []): Collection
    {
        return $this->filterQuery($querys)->get();
    }

    public function findById(int $id): ?Product
    {
        return $this->product->find($id);
    }

    public function create(array $data): Product
    {
        try {
            DB::beginTransaction();
            return $this->product->create($data);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $code = strval(time());
            Log::error('ProductRepository->store code: '.$code.' message: '.$e->getMessage().' data: ',$data);
            throw new ErrorException('Unexpected error. code: '.$code);
        }
    }

    public function updateById(Product $product, array $data): bool
    {
        try {
            DB::beginTransaction();
            return $product->update($data);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $code = strval(time());
            Log::error('ProductRepository->update code: '.$code.' message: '.$e->getMessage().' data: ',$data);
            throw new ErrorException('Unexpected error. code: '.$code);
        }
    }

    public function deleteById(Product $product): bool
    {
        try {
            DB::beginTransaction();
            return $product->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $code = strval(time());
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