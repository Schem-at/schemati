<?php

namespace App\Http\Controllers;

use App\Http\Requests\PasswordSetRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PasswordSet extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(PasswordSetRequest $request)
    {
        $user = User::firstOrCreate([
            'uuid' => $request->player_uuid,
        ]);
        $user->password = Hash::make($request->password);
        $user->save();
        return response()->json(
            [
                'message' => 'Password set successfully',
            ],
            200,
        );
    }
}
