<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Auth;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $responseData = [
            'status' => 'false',
            'data' => []
        ];
        $code = 200;

        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                $responseData['data']['message'] = 'Token is Invalid';
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                try {
                    $refreshed = JWTAuth::refresh(JWTAuth::getToken());
                    $response->header('Authorization', 'Bearer ' . $refreshed);
                } catch (JWTException $e) {
                    $responseData['data']['message'] = 'Cannot Generated New Token';
                }
                $user = JWTAuth::setToken($refreshed)->toUser();
            }else{
                $responseData['data']['message'] = 'Authorization Token not found';
                $code = 500;
            }
            return response()->json($responseData, $code);
        }

        Auth::guard('api')->login($user, false);
        return $response;
    }
}
