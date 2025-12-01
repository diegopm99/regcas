<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * Respuesta de éxito estándar
     */
    protected function successResponse(string $message = '', $data = null, int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ], $code);
    }

    /**
     * Respuesta de error estándar
     */
    protected function errorResponse(string $message = '', int $code = 500, $data = null): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data'    => $data,
        ], $code);
    }
}
