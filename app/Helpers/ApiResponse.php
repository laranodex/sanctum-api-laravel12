<?php

namespace App\Helpers;

class ApiResponse
{
    public static function success(
        string $message,
        mixed $data = null,
        int $statusCode = 200
    ) {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    public static function error(
        string $message,
        mixed $errors = null,
        int $statusCode = 400
    ) {
        return response()->json([
            'status' => false,
            'message' => $message,
            'errors' => $errors,
        ], $statusCode);
    }
}
