<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ProductService
{
    public function __construct(
        private ProductRepository $repository
    )
    {
    }

    public function index(array $queryParms): Collection
    {
        return $this->repository->index(
            $this->validateQueryParms($queryParms)
        );
    }

    public function show($product): Product
    {
        $product = $this->repository->show($this->validateId($product));

        \throw_if($product == \null, new HttpException(404, 'Product not found.'));

        return $product;
    }

    public function store(array $data): Product
    {
        return $this->repository->store($data); 
    }

    public function update($product, array $data): string
    {
        $product = $this->repository->show($this->validateId($product));

        \throw_if($product == \null, new HttpException(404, 'Product not found.'));

        if ($this->repository->update($product, $data)) {
            return 'updated successfully';
        }

        return 'error on updated';
    }

    // public function destroy(Product $product)
    // {
    //     //
    // }

    private function validateQueryParms(array $queryParms): array
    {
        $querys = [];

        if (isset($queryParms['name'])) {
            $querys['name'] = $queryParms['name'];
        }

        if (isset($queryParms['category'])) {
            $querys['category'] = $queryParms['category'];
        }

        if (isset($queryParms['image_url'])) {
            if ($queryParms['image_url'] == 'true') {
                $querys['image_url'] = true;
            }

            if ($queryParms['image_url'] == 'false') {
                $querys['image_url'] = false;
            }
        }

        return $querys;
    }

    private function validateId($product): int
    {
        $id = intval($product);

        \throw_if(
            $id == 0,
            new HttpException(400, 'Parameter does not correspond to an ID.')
        );

        return $id;
    }
}