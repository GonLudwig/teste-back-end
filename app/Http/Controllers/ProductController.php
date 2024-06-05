<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Services\ProductService;
use App\Traits\HandlesApiExceptions;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ProductController extends Controller
{
    use HandlesApiExceptions;

    public function __construct(
        private ProductService $productService
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        return $this->HandlesExceptions(function () use ($request) {
            return response()->json(
                $this->productService->listAll($request->query())
            );
        });
    }

    public function show(mixed $product): JsonResponse
    {
        return $this->HandlesExceptions(function () use ($product) {
            return response()->json(
                $this->productService->findById($product)
            );
        });
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        return $this->HandlesExceptions(function () use ($request) {
            return response()->json(
                $this->productService->create($this->structureBody($request)),
                201
            );
        });
    }

    public function update(UpdateProductRequest $request, mixed $product): JsonResponse
    {
        return $this->HandlesExceptions(function () use ($product, $request) {
            $message = ['message' => 'error on updated'];

            if ($this->productService->updateById($product, $this->structureBody($request))) {
                Arr::set($message, 'message', 'updated sucessfuly');
            }

            return response()->json($message);
        });
    }

    public function destroy(mixed $product): JsonResponse
    {
        return $this->HandlesExceptions(function () use ($product) {
            $message = ['message' => 'error on deleted'];

            if ($this->productService->deleteById($product)) {
                Arr::set($message, 'message', 'deleted successfully');
            }

            return response()->json($message);
        });
    }

    private function structureBody(FormRequest $request): array
    {
        $body = [...$request->validated()];

        if ($request->has('image')) {
            Arr::set($body, 'image_url', $request->input('image'));
        }

        return $body;
    }
}
