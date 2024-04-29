<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class EnsureValidJWT
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json([
                'error' => 'Token not provided.'
            ], 401);
        }
        try {
            $payload = JWT::decode($token, new Key(config('app.jwt_secret'), 'HS256'));
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Token is invalid.'
            ], 401);
        }
        $iss = $payload->iss;
        $iat = $payload->iat;
        return $next($request);
    }
}
