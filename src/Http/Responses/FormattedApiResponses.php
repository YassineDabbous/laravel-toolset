<?php

namespace Yaseen\Toolset\Http\Responses;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Symfony\Component\HttpFoundation\Response;

/**
 * Trait FormattedApiResponses
 *
 * Provides standardized JSON response methods for API controllers.
 * This manages the "envelope" of the response (status, message, errors).
 */
trait FormattedApiResponses
{
    /**
     * The single source of truth for our API response structure.
     *
     * @param mixed|null $data The main data payload.
     * @param int $status The HTTP status code.
     * @param string|null $message A descriptive message.
     * @param string|null $error An error message.
     * @param mixed|null $validation Validation errors.
     * @param int $errorCode An application-specific error code.
     * @param bool $forceJson Force an empty array to be returned as {} instead of [].
     * @param array $headers Additional headers to include in the response.
     * 
     * @return JsonResponse
     */
    public static function standardize(
        $data,
        int $status,
        ?string $message = null,
        $error = null,
        $validation = null,
        int $errorCode = 0,
        bool $forceJson = false,
        array $headers = [],
    ): JsonResponse {
        return response()->json(
            data: array_filter(
                [
                    'code' => $errorCode,
                    'message' => $message,
                    'error' => $error,
                    'data' => $data,
                    'validation' => $validation,
                ],
                fn ($v) => !is_null($v)
            ),
            status: $status,
            headers: $headers,
            options: $forceJson ? JSON_FORCE_OBJECT : 0
        );
    }

    /**
     * Return a successful response.
     * @param mixed|null $data The main data payload.
     * @param string $message A descriptive message.
     * @param int $status The HTTP status code.
     * @param bool $forceJson Force an empty array to be returned as {} instead of [].
     * @param int|null $cacheInSeconds Number of seconds to cache the response.
     */
    public static function success(
        $data = null,
        string $message = 'success',
        int $status = Response::HTTP_OK,
        bool $forceJson = false,
        ?int $cacheInSeconds = null,
    ): JsonResponse
    {
        if ($data instanceof ResourceCollection
            && $data->resource instanceof Paginator) {
            $p = $data->resource;
            $items = $data->resolve(request());
            $data = [
                'data' => $items,
                'current_page' => $p->currentPage(),
                'per_page' => $p->perPage(),
                'has_more' => $p->hasMorePages(),
            ];
            if ($p instanceof LengthAwarePaginator) {
                $data['total'] = $p->total();
                $data['last_page'] = $p->lastPage();
            }
        } elseif ($data instanceof JsonResource) {
            $data = $data->resolve(request());
        }

        $headers = [];
        if ($cacheInSeconds) {
            $headers = [
                'Cache-Control' => "public, max-age=$cacheInSeconds",
                'ETag'          => md5(json_encode($data)),
            ];
        }

        return static::standardize(
            data: $data,
            status: $status,
            message: $message,
            forceJson: $forceJson,
            headers: $headers,
        );
    }

    /**
     * Return a generic error response.
     * 
     * @param string|null $error An error message.
     * @param int $status The HTTP status code.
     * @param int $errorCode An application-specific error code.
     */
    public static function error(
        ?string $error,
        int $status = Response::HTTP_BAD_REQUEST,
        int $errorCode = 0
    ): JsonResponse {
        return static::standardize(data: null, status: $status, error: $error, errorCode: $errorCode);
    }

    /**
     * Return an unauthenticated response.
     * 
     * @param string|null $error An error message.
     */
    public static function unauthenticated(?string $error = 'Unauthenticated.'): JsonResponse
    {
        return static::standardize(null, Response::HTTP_UNAUTHORIZED, error: $error);
    }

    /**
     * Return an unauthorized response.
     * 
     * @param string|null $error An error message.
     */
    public static function unauthorized(?string $error = 'This action is unauthorized.'): JsonResponse
    {
        return static::standardize(null, Response::HTTP_FORBIDDEN, error: $error);
    }

    /**
     * Return a not found response.
     * 
     * @param string|null $error An error message.
     */
    public static function notFound(?string $error = 'Resource not found.'): JsonResponse
    {
        return static::standardize(null, Response::HTTP_NOT_FOUND, error: $error);
    }

    /**
     * Return a validation error response.
     * 
     * @param mixed $validation Validation error details.
     */
    public static function validation($validation): JsonResponse
    {
        return static::standardize(
            data: null,
            status: Response::HTTP_UNPROCESSABLE_ENTITY,
            message: 'The given data was invalid.',
            validation: $validation
        );
    }
}