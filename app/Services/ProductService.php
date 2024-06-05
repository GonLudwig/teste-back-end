<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ProductService
{
    public function __construct(
        private ProductRepository $productRepository
    )
    {
    }

    public function listAll(array $queryParms = []): Collection
    {
        return $this->productRepository->all(
            $this->validateQueryParms($queryParms)
        );
    }

    public function findById(mixed $product): Product
    {
        $product = $this->productRepository->findById($this->validateId($product));

        throw_if($product == null, new HttpException(404, 'Product not found.'));

        return $product;
    }

    public function create(array $data): Product
    {
        return $this->productRepository->create($data); 
    }

    public function updateById(mixed $product, array $data): bool
    {
        $product = $this->productRepository->findById($this->validateId($product));

        throw_if($product == null, new HttpException(404, 'Product not found.'));

        return $this->productRepository->updateById($product, $data);
    }

    public function deleteById(mixed $product): bool
    {
        $product = $this->productRepository->findById($this->validateId($product));

        throw_if($product == null, new HttpException(404, 'Product not found.'));

        return $this->productRepository->deleteById($product);
    }

    private function validateQueryParms(array $queryParms): array
    {
        $querys = [];

        if (empty($queryParms)) {
            return $querys;
        }

        if (isset($queryParms['name'])) {
            Arr::set(
                $querys,
                'name',
                Arr::get($queryParms, 'name')
            );
        }

        if (isset($queryParms['category'])) {
            Arr::set(
                $querys,
                'category',
                Arr::get($queryParms, 'category')
            );
        }

        if (isset($queryParms['image_url'])) {
            if (Arr::get($queryParms, 'image_url') == 'true') {
                Arr::set(
                    $querys,
                    'image_url',
                    true
                );
            }

            if (Arr::get($queryParms, 'image_url') == 'false') {
                Arr::set(
                    $querys,
                    'image_url',
                    false
                );
            }
        }

        return $querys;
    }

    private function validateId(mixed $product): int
    {
        $id = intval($product);

        throw_if(
            $id == 0,
            new HttpException(400, 'Parameter does not correspond to an ID.')
        );

        return $id;
    }
}