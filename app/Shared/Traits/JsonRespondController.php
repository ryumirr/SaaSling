<?php

namespace App\Shared\Traits;

use Illuminate\Http\JsonResponse;

trait JsonRespondController
{
    protected int $statusCode = 200;

    protected function respond(mixed $data = null, int $statusCode = 200): JsonResponse
    {
        return response()->json($data, $statusCode);
    }

    // 2xx
    protected function respondOk(mixed $data = null): JsonResponse
    {
        return $this->respond($data, 200);
    }

    protected function respondCreated(mixed $data = null): JsonResponse
    {
        return $this->respond($data, 201);
    }

    protected function respondNoContent(): JsonResponse
    {
        return $this->respond(null, 204);
    }

    // 4xx
    protected function respondBadRequest(string $message = 'Bad Request'): JsonResponse
    {
        return $this->respond(['message' => $message], 400);
    }

    protected function respondUnauthorized(string $message = 'Unauthorized'): JsonResponse
    {
        return $this->respond(['message' => $message], 401);
    }

    protected function respondForbidden(string $message = 'Forbidden'): JsonResponse
    {
        return $this->respond(['message' => $message], 403);
    }

    protected function respondNotFound(string $message = 'Not Found'): JsonResponse
    {
        return $this->respond(['message' => $message], 404);
    }

    protected function respondConflict(string $message = 'Conflict'): JsonResponse
    {
        return $this->respond(['message' => $message], 409);
    }

    protected function respondUnprocessableEntity(mixed $errors = null, string $message = 'Unprocessable Entity'): JsonResponse
    {
        return $this->respond([
            'message' => $message,
            'errors'  => $errors,
        ], 422);
    }

    protected function respondTooManyRequests(string $message = 'Too Many Requests'): JsonResponse
    {
        return $this->respond(['message' => $message], 429);
    }

    // 5xx
    protected function respondInternalError(string $message = 'Internal Server Error'): JsonResponse
    {
        return $this->respond(['message' => $message], 500);
    }
}
