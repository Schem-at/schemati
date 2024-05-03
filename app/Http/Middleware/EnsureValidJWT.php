<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class EnsureValidJWT
{
    public function handle(Request $request, Closure $next, ...$permissions)
    {
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json(
                [
                    'error' => 'Token not provided.',
                ],
                401,
            );
        }

        try {
            $payload = JWT::decode($token, new Key(config('app.jwt_secret'), 'HS256'));
        } catch (\Exception $e) {
            return response()->json(
                [
                    'error' => 'Token is invalid.',
                ],
                401,
            );
        }

        foreach ($permissions as $permission) {
            if (!isset($payload->permissions) || !in_array($permission, $payload->permissions)) {
                return response()->json(
                    [
                        'error' => 'Insufficient permissions.',
                    ],
                    403,
                );
            }
        }

        return $next($request);
    }
}
