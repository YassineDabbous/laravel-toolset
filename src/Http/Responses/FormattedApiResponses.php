<?php

namespace Yaseen\Toolset\Http\Responses;

use Illuminate\Http\JsonResponse;
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
     * @return JsonResponse
     */
    public static function standardize(
        $data,
        int $status,
        ?string $message = null,
        $error = null,
        $validation = null,
        int $errorCode = 0,
        bool $forceJson = false
    ): JsonResponse {
        return response()->json(
            array_filter(
                [
                    'code' => $errorCode,
                    'message' => $message,
                    'error' => $error,
                    'data' => $data,
                    'validation' => $validation,
                ],
                fn ($v) => !is_null($v)
            ),
            $status,
            [],
            $forceJson ? JSON_FORCE_OBJECT : 0
        );
    }

    /**
     * Return a successful response.
     */
    public static function success($data = null, string $message = 'success', bool $forceJson = false): JsonResponse
    {
        return static::standardize(
            data: $data,
            status: Response::HTTP_OK,
            message: $message,
            forceJson: $forceJson
        );
    }

    /**
     * Return a generic error response.
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
     */
    public static function unauthenticated(?string $error = 'Unauthenticated.'): JsonResponse
    {
        return static::standardize(null, Response::HTTP_UNAUTHORIZED, error: $error);
    }

    /**
     * Return an unauthorized response.
     */
    public static function unauthorized(?string $error = 'This action is unauthorized.'): JsonResponse
    {
        return static::standardize(null, Response::HTTP_FORBIDDEN, error: $error);
    }

    /**
     * Return a not found response.
     */
    public static function notFound(?string $error = 'Resource not found.'): JsonResponse
    {
        return static::standardize(null, Response::HTTP_NOT_FOUND, error: $error);
    }

    /**
     * Return a validation failure response.
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