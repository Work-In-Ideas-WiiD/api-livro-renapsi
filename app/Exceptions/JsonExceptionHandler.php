<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Throwable;

class JsonExceptionHandler extends ExceptionHandler
{
    public function render($request, Throwable $e): Response|JsonResponse|RedirectResponse|\Symfony\Component\HttpFoundation\Response
    {
        // Check if the request wants JSON response
        if ($request->expectsJson() && config('app.env') === 'production') {
            return $this->handleJsonResponse($e);
        }

        return parent::render($request, $e);
    }

    protected function handleJsonResponse(Throwable $exception): JsonResponse
    {
        return response()->json(['error' => $exception->getMessage()], 500);
    }
}
