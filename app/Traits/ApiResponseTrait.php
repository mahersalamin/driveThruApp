<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponseTrait
{
    /**
     * Success response
     */
    protected function successResponse($data = null, string $message = '', int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ], $code);
    }

    /**
     * Error response
     */
    protected function errorResponse(string $message = 'Something went wrong.', int $code = 500, array $errors = []): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors'  => !empty($errors) ? $errors : null,
        ], $code);
    }

    /**
     * Not Found response
     */
    protected function notFoundResponse(string $message = 'Resource not found.'): JsonResponse
    {
        return $this->errorResponse($message, 404);
    }

    /**
     * Validation Error response
     */
    protected function validationErrorResponse(array $errors = [], string $message = 'Validation failed.'): JsonResponse
    {
        return $this->errorResponse($message, 422, $errors);
    }

    /**
     * Unauthorized response
     */
    protected function unauthorizedResponse(string $message = 'Unauthorized.'): JsonResponse
    {
        return $this->errorResponse($message, 401);
    }

    /**
     * Forbidden response
     */
    protected function forbiddenResponse(string $message = 'Forbidden.'): JsonResponse
    {
        return $this->errorResponse($message, 403);
    }
}
