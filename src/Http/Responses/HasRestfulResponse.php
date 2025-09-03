<?php

namespace Ysn\SuperCore\Http\Responses;
use Illuminate\Http\JsonResponse;


trait HasRestfulResponse
{

    public static function standardize($data, int $status, ?string $message = null, ?string $error = null, $validation = null, int $errorCode = 0, $forceJson = false) : JsonResponse
    {
        //array_filter will remove null|false|0 values but you can pass closure fn($value) => !is_null($value) && $value !== ''
        return response()->json(
                array_filter(
                    [
                        'code' => $errorCode,
                        'message' => $message,
                        'data' => $data,
                        'error' => $error,
                        'validation' => $validation,
                    ],
                    fn($v) => !is_null($v)
                ), 
                $status,
                [],
                $forceJson ? JSON_FORCE_OBJECT : 0
            );
    }

    public static function success($data = null, $msg = 'success', $forceJson = false)
    {
        return static::standardize($data, 200, $msg, null, null, $forceJson);
    }

    public static function error(?string $error, int $errorCode = 0)
    {
        // 409 Conflict
        return static::standardize(null, 409, error: $error, errorCode: $errorCode);
    }

    public static function failure(?array $errors)
    {
        return static::standardize(null, 500, null, $errors, null);
    }

    public static function unauthenticated(?string $error = 'unauthenticated')
    {
        return static::standardize(null, 401, null, $error, null);
    }

    public static function unauthorized(?string $error = 'unauthorized')
    {
        return static::standardize(null, 403, null, $error, null);
    }

    public static function notFound(?string $error='not found')
    {
        return static::standardize(null, 404, null, $error, null);
    }
    public static function notImplemented()
    {
        return static::standardize(null, 501, 'Not implemented yet', null, null); //Response::HTTP_NOT_IMPLEMENTED
    }

    public static function validation($validation)
    {
        return static::standardize(null, 422, null, null, $validation);
    }

}
