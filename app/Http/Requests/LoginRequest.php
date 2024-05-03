<?php
namespace App\Http\Requests;

use Laravel\Fortify\Http\Requests\LoginRequest as FortifyLoginRequest;
use Laravel\Fortify\Fortify;

use Illuminate\Support\Facades\Cache;

use App\Utils\MinecraftAPI;

class LoginRequest extends FortifyLoginRequest
{
    const CACHE_TTL = 60 * 60 * 24; // 24 hours
    public function authorize()
    {
        $uuid = Cache::remember('uuid-' . $this->username, self::CACHE_TTL, function () {
            return MinecraftAPI::getUUID($this->username);
        });
        if ($uuid) {
            $this->merge([
                Fortify::username() => $uuid,
            ]);
        } else {
            return false;
        }
        return true;
    }

    public function rules()
    {
        return [
            Fortify::username() => 'required|string',
            'password' => 'required|string',
            'myAttribute' => 'rules',
        ];
    }
}
