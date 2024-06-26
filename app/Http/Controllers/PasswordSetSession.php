<?php

namespace App\Http\Controllers;

use App\Http\Requests\PasswordSetSessionRequest;
use App\Utils\CommonUtils;
use Illuminate\Support\Facades\Cache;

/**
 * @tags User
 */
class PasswordSetSession extends Controller
{
    /**
     * Create a password set session
    *
     * This endpoint is used to create a password set session. When a user visits the link provided, they will be able to set their password.
     */
    public function __invoke(PasswordSetSessionRequest $request)
    {
        $shortCode = CommonUtils::randomString(8);
        $cacheKey = "password-set-session:{$shortCode}";
        Cache::put($cacheKey, $request->player_uuid, now()->addMinutes(5));
        $link = route('set-password') . "?session={$shortCode}";
        return response()->json(
            [
                'link' => $link,
            ],
            200,
        );
    }
}
