<?php

declare(strict_types=1);

namespace App\Presentation\Shared\Traits;

use App\Domain\Shared\Pagination\PaginatedResult;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponse
{
    protected function success(
        mixed $data = null,
        string $message = 'Success',
        int $code = Response::HTTP_OK,
        array $headers = []
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ], $code, $headers);
    }

    protected function created(
        mixed $data = null,
        string $message = 'Resource created successfully',
        array $headers = []
    ): JsonResponse {
        return $this->success($data, $message, Response::HTTP_CREATED, $headers);
    }

    protected function noContent(array $headers = []): JsonResponse
    {
        return response()->json(null, Response::HTTP_NO_CONTENT, $headers);
    }

    /**
     * @param array<string, mixed> $errors
     */
    protected function error(
        string $message = 'Error',
        int $code = Response::HTTP_BAD_REQUEST,
        array $errors = [],
        array $headers = []
    ): JsonResponse {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors !== []) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code, $headers);
    }

    protected function notFound(string $message = 'Resource not found', array $headers = []): JsonResponse
    {
        return $this->error($message, Response::HTTP_NOT_FOUND, [], $headers);
    }

    protected function unauthorized(string $message = 'Unauthorized', array $headers = []): JsonResponse
    {
        return $this->error($message, Response::HTTP_UNAUTHORIZED, [], $headers);
    }

    protected function forbidden(string $message = 'Forbidden', array $headers = []): JsonResponse
    {
        return $this->error($message, Response::HTTP_FORBIDDEN, [], $headers);
    }

    /**
     * @param array<string, mixed> $errors
     */
    protected function validationError(array $errors, string $message = 'Validation failed', array $headers = []): JsonResponse
    {
        return $this->error($message, Response::HTTP_UNPROCESSABLE_ENTITY, $errors, $headers);
    }
 protected function paginated(
        PaginatedResult $paginatedResult,
        mixed $data,
        string $message = 'Success',
        int $code = Response::HTTP_OK,
        array $headers = []
    ): JsonResponse {

        return response()->json([
            'success'    => true,
            'message'    => $message,
            'data'       => $data,
            'pagination' => [
                'current_page' => $paginatedResult->currentPage,
                'last_page'    => $paginatedResult->lastPage,
                'per_page'     => $paginatedResult->perPage,
                'total'        => $paginatedResult->total,
                'next_page_url' => null,
                'prev_page_url' => null,
            ],
        ], $code, $headers);
    }
}
