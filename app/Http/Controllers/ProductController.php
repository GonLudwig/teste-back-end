<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ProductController extends Controller
{
    public function __construct(
        private ProductService $service
    )
    {
    }

    public function index(Request $request)
    {
        try {
            return \response()->json(
                $this->service->index($request->query())
            );
        } catch (HttpException $e) {
            if ($e->getStatusCode() < 500) {
                Log::info('message: '.$e->getMessage());
                return \response()->json(['message' => $e->getMessage()], $e->getStatusCode());
            } else {
                $code = strval(\time());
                Log::error('code: '.$code.' message: '.$e->getMessage());
                return \response()->json(
                    ['message' => 'Erro inesperado codigo: '.$code], $e->getStatusCode()
                );
            }
        } catch (\Exception $e) {
            $code = strval(\time());
            Log::error('code: '.$code.' message: '.$e->getMessage());
            return \response()->json(['message' => 'Erro inesperado codigo: '.$code], 500);
        }

    }

    public function show($product)
    {
        try {
            return \response()->json(
                $this->service->show($product)
            );
        } catch (HttpException $e) {
            if ($e->getStatusCode() < 500) {
                Log::info('message: '.$e->getMessage());
                return \response()->json(['message' => $e->getMessage()], $e->getStatusCode());
            } else {
                $code = strval(\time());
                Log::error('code: '.$code.' message: '.$e->getMessage());
                return \response()->json(
                    ['message' => 'Erro inesperado codigo: '.$code], $e->getStatusCode()
                );
            }
        } catch (\Exception $e) {
            $code = strval(\time());
            Log::error('code: '.$code.' message: '.$e->getMessage());
            return \response()->json(['message' => 'Erro inesperado codigo: '.$code], 500);
        }
    }

    public function store(StoreProductRequest $request)
    {
        try {
            return \response()->json(
                $this->service->store($request->validated()),
                201
            );
        } catch (HttpException $e) {
            if ($e->getStatusCode() < 500) {
                Log::info('message: '.$e->getMessage());
                return \response()->json(['message' => $e->getMessage()], $e->getStatusCode());
            } else {
                $code = strval(\time());
                Log::error('code: '.$code.' message: '.$e->getMessage());
                return \response()->json(
                    ['message' => 'Erro inesperado codigo: '.$code], $e->getStatusCode()
                );
            }
        } catch (\Exception $e) {
            $code = strval(\time());
            Log::error('code: '.$code.' message: '.$e->getMessage());
            return \response()->json(['message' => 'Erro inesperado codigo: '.$code], 500);
        }
    }

    public function update(UpdateProductRequest $request, $product)
    {
        try {
            return \response()->json([
                'message' => $this->service->update($product, $request->validated())
            ]);
        } catch (HttpException $e) {
            if ($e->getStatusCode() < 500) {
                Log::info('message: '.$e->getMessage());
                return \response()->json(['message' => $e->getMessage()], $e->getStatusCode());
            } else {
                $code = strval(\time());
                Log::error('code: '.$code.' message: '.$e->getMessage());
                return \response()->json(
                    ['message' => 'Erro inesperado codigo: '.$code], $e->getStatusCode()
                );
            }
        } catch (\Exception $e) {
            $code = strval(\time());
            Log::error('code: '.$code.' message: '.$e->getMessage());
            return \response()->json(['message' => 'Erro inesperado codigo: '.$code], 500);
        }
    }

    // public function destroy($product)
    // {
    //     try {
    //         return \response()->json(
    //             $this->service->destroy()
    //         );
    //     } catch (HttpException $e) {
    //         if ($e->getStatusCode() < 500) {
    //             Log::info('message: '.$e->getMessage());
    //             return \response()->json(['message' => $e->getMessage()], $e->getStatusCode());
    //         } else {
    //             $code = strval(\time());
    //             Log::error('code: '.$code.' message: '.$e->getMessage());
    //             return \response()->json(
    //                 ['message' => 'Erro inesperado codigo: '.$code], $e->getStatusCode()
    //             );
    //         }
    //     } catch (\Exception $e) {
    //         $code = strval(\time());
    //         Log::error('code: '.$code.' message: '.$e->getMessage());
    //         return \response()->json(['message' => 'Erro inesperado codigo: '.$code], 500);
    //     }
    // }
}
