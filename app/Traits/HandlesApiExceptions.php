<?php

namespace App\Traits;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;

trait HandlesApiExceptions
{
    public static function HandlesExceptions(callable $callback): JsonResponse
    {
        try {
            return $callback();
        } catch (HttpException $e) {
            Log::info('message: '.$e->getMessage());

            return response()->json(['message' => $e->getMessage()], $e->getStatusCode());
        } catch (Exception $e) {
            $code = strval(time());
            Log::error('code: '.$code.' message: '.$e->getMessage());

            return response()->json(['message' => 'Unexpected error. code: '.$code], 500);
        }
    }
}
