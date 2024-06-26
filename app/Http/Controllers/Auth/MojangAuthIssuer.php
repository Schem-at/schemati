<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\JWT;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\MojangAuthIssuerRequest;
use Illuminate\Support\Facades\Http;

/**
 * @tags Auth
 */
class MojangAuthIssuer extends Controller
{
    /**
    * Mojang Auth Issuer
    *
    * The Mojang Auth Issuer endpoint is used to authenticate a user using their Mojang account from a Minecraft client.
    */
    public function __invoke(MojangAuthIssuerRequest $request)
    {
        $response = Http::get("https://sessionserver.mojang.com/session/minecraft/hasJoined?username={$request->username}&serverId={$request->serverId}");

        if (!$response->ok()) {
            return response()->json(
                [
                    'error' => 'Invalid username or serverId',
                ],
                401
            );
        }

        return response()->json([
            'token' => JWT::getToken([
                'username' => $response['name'],
                'uuid' => $response['id'],
            ])
        ], 200);
    }
}
