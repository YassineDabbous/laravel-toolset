<?php
namespace Ysn\SuperCore\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Ysn\SuperCore\Http\Responses\HasRestfulResponse; 

class BaseController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    use HasRestfulResponse;


    /**
     * @param \Ysn\SuperCore\Models\BaseAccount $account
     * @param  string  $deviceName
     */
    public function authResponse($account, $deviceName, array $abilities = ['*'])
    {
        $token = $account->createToken(name: $deviceName ?? 'UnknownDevice', abilities:$abilities)->plainTextToken;
        return $this->authRefreshResponse($account, $token);
    }
    public function authRefreshResponse($account, $token)
    {
        return $this->success([
            'id' => $account->id,
            'name' => $account->name,
            'type' => $account->type,
            'category_id' => $account->category_id,
            'photo' => $account->photo,
            'token' => $token,
            'permissions' => $account->getAllPermissionsIds(),
            //'abilities' => $account->currentAccessToken()->abilities,
        ]);
    }




    // public static function standardize($data, int $status, ?string $message = null, ?string $error = null, $validation = null, int $errorCode = 0)
    // {
    //     //array_filter will remove null|false|0 values but you can pass closure fn($value) => !is_null($value) && $value !== ''
    //     return response()->json(
    //             array_filter(
    //                 [
    //                     'code' => $errorCode,
    //                     'message' => $message,
    //                     'data' => $data,
    //                     'error' => $error,
    //                     'validation' => $validation,
    //                 ],
    //                 fn($v) => !is_null($v)
    //             ), 
    //             $status
    //         );
    // }

    // public static function success($data = null, $msg = 'success')
    // {
    //     return static::standardize($data, 200, $msg, null, null);
    // }

    // public static function error(?string $error, int $errorCode = 0)
    // {
    //     // 409 Conflict
    //     return static::standardize(null, 409, error: $error, errorCode: $errorCode);
    // }

    // public static function failure(?array $errors)
    // {
    //     return static::standardize(null, 500, null, $errors, null);
    // }

    // public static function unauthenticated(?string $error = 'unauthenticated')
    // {
    //     return static::standardize(null, 401, null, $error, null);
    // }

    // public static function unauthorized(?string $error = 'unauthorized')
    // {
    //     return static::standardize(null, 403, null, $error, null);
    // }

    // public static function notFound(?string $error='not found')
    // {
    //     return static::standardize(null, 404, null, $error, null);
    // }
    // public static function notImplemented()
    // {
    //     return static::standardize(null, 501, 'Not implemented yet', null, null); //Response::HTTP_NOT_IMPLEMENTED
    // }

    // public static function validation($validation)
    // {
    //     return static::standardize(null, 422, null, null, $validation);
    // }



}
