<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class ResponseHandler
{
    /**
     * Success Response
     *
     * @param string $message
     * @param array|null $data
     * @param int $statusCode
     * @return JsonResponse
     */
    public static function success(string $message, array $data = null, int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'statusCode' => $statusCode,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    /**
     * Error Response
     *
     * @param string $message
     * @param array|null $errors
     * @param int $statusCode
     * @return JsonResponse
     */
    public static function error(string $message, array $errors = null, int $statusCode = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'statusCode' => $statusCode,
            'message' => $message,
            'errors' => $errors,
        ], $statusCode);
    }
}
