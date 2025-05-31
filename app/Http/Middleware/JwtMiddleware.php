<?php

namespace App\Http\Middleware;

use Closure;
use DateTime;
use Exception;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware extends BaseMiddleware
{
    public function handle($request, Closure $next, $roles = '')
    {
        try {
            $userModel = JWTAuth::parseToken()->authenticate();
            $userToken = JWTAuth::parseToken()->getPayload()->get('user');

            $updatedDb = new DateTime($userModel['updated_security']);
            $updatedToken = new DateTime($userToken['updated_security']);

            // Cek jika ada perubahan pengaturan keamanan, user harus login ulang
            if ($updatedDb > $updatedToken) {
                return response()->failed(['Terdapat perubahan pengaturan keamanan, silahkan login ulang'], 403);
            }
        } catch (Exception $e) {
            if ($e instanceof TokenInvalidException) {
                return response()->failed(['Token yang anda gunakan tidak valid'], 403);
            } elseif ($e instanceof TokenExpiredException) {
                return response()->failed(['Token anda telah kadaluarsa, silahkan login ulang'], 403);
            } else {
                return response()->failed(['Silahkan login terlebih dahulu. ' . $e->getMessage()], 403);
            }
        }

        return $next($request);
    }
}
