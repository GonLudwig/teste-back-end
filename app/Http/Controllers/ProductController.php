<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Services\ProductService;
use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ProductController extends Controller
{
    public function __construct(
        private ProductService $productService
    )
    {
    }

    public function index(Request $request): JsonResponse
    {
        try {
            return response()->json(
                $this->productService->listAll($request->query())
            );
        } catch (HttpException $e) {
            Log::info('message: '.$e->getMessage());
            return response()->json(['message' => $e->getMessage()], $e->getStatusCode());
        } catch (Exception $e) {
            $code = strval(time());
            Log::error('code: '.$code.' message: '.$e->getMessage());
            return response()->json(['message' => 'Unexpected error. code: '.$code], 500);
        }

    }

    public function show(mixed $product): JsonResponse
    {
        try {
            return response()->json(
                $this->productService->findById($product)
            );
        } catch (HttpException $e) {
            Log::info('message: '.$e->getMessage());
            return response()->json(['message' => $e->getMessage()], $e->getStatusCode());
        } catch (Exception $e) {
            $code = strval(time());
            Log::error('code: '.$code.' message: '.$e->getMessage());
            return response()->json(['message' => 'Unexpected error. code: '.$code], 500);
        }
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        try {
            return response()->json(
                $this->productService->create($this->structureBody($request)),
                201
            );
        } catch (HttpException $e) {
            Log::info('message: '.$e->getMessage());
            return response()->json(['message' => $e->getMessage()], $e->getStatusCode());
        } catch (Exception $e) {
            $code = strval(time());
            Log::error('code: '.$code.' message: '.$e->getMessage());
            return response()->json(['message' => 'Unexpected error. code: '.$code], 500);
        }
    }

    public function update(UpdateProductRequest $request, mixed $product): JsonResponse
    {
        $message = ['message' => 'error on updated'];

        try {
            if ($this->productService->updateById($product, $this->structureBody($request))) {
                Arr::set($message, 'message', 'updated sucessfuly');
            }
            return response()->json($message);
        } catch (HttpException $e) {
            Log::info('message: '.$e->getMessage());
            return response()->json(['message' => $e->getMessage()], $e->getStatusCode());
        } catch (Exception $e) {
            $code = strval(time());
            Log::error('code: '.$code.' message: '.$e->getMessage());
            return response()->json(['message' => 'Unexpected error. code: '.$code], 500);
        }
    }

    public function destroy(mixed $product): JsonResponse
    {

        $message = ['message' => 'error on deleted'];

        try {
            if ($this->productService->deleteById($product)) {
                Arr::set($message, 'message', 'deleted successfully');
            }
            return response()->json($message);
        } catch (HttpException $e) {
            Log::info('message: '.$e->getMessage());
            return response()->json(['message' => $e->getMessage()], $e->getStatusCode());
        } catch (Exception $e) {
            $code = strval(time());
            Log::error('code: '.$code.' message: '.$e->getMessage());
            return response()->json(['message' => 'Unexpected error. code: '.$code], 500);
        }
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
