<?php
namespace App\Helpers;

use Firebase\JWT\JWT as FirebaseJWT;
use Firebase\JWT\Key;
use App\Models\Player;
use App\Models\Tag;
use Carbon\Carbon;

class JWT
{
    public static function getTagBody(Player $player, array $tags, Carbon $exp = null)
    {
        $tagIds = $tags->map(function ($tag) {
            return $tag->id;
        });
        $body = [
            'player' => $player,
            'tags' => $tagIds,
        ];

        if ($exp) {
            $body['exp'] = $exp->timestamp;
        }

        return $body;
    }

    public static function getToken($payload)
    {
        return FirebaseJWT::encode($payload, config('app.jwt_secret'), 'HS256');
    }

    public static function getTagToken(Player $player, array $tags)
    {
        return self::getToken(self::getTagBody($player, $tags));
    }

    public static function getTestToken($name = 'Nano_')
    {
        $player = Player::firstOrCreate(['last_seen_name' => $name]);
        $tag = Tag::getRoot();
        return self::getTagToken($player, [$tag]);
    }
}
