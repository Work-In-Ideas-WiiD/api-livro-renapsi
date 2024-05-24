<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class BaseApiController extends Controller
{
    public function sendResponse(string $message = '', $is_success = false, $code = 400): JsonResponse
    {
        $response = [
            'success' => $is_success,
            'message' => $message,
        ];

        return response()->json($response, $code);
    }
}
