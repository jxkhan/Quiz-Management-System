<?php

namespace App\Helpers;

class ResponseHelper
{
    public static function success($data = null, $message = 'Success', $status = 200)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $status);
    }

    public static function error($message = 'Error', $status = 400, $data = null)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'data' => $data
        ], $status);
    }

    public static function jsonResponse($success, $data = [], $statusCode = 200)
    {
        return response()->json([
            'success' => $success,
            'data' => $data,
        ], $statusCode);
    }
}
