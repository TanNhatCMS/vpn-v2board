<?php

namespace App\Services;

use App\Models\User;
use App\Utils\CacheKey;
use App\Utils\Helper;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AuthService
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function generateAuthData(Request $request): array
    {
        $guid = Helper::guid();
        $authData = JWT::encode([
            'id' => $this->user->id,
            'session' => $guid,
        ], config('app.key'), 'HS256');
        self::addSession($this->user->id, $guid, [
            'ip' => $request->ip(),
            'login_at' => time(),
            'ua' => $request->userAgent(),
        ]);

        return [
            'token' => $this->user->token,
            'is_admin' => $this->user->is_admin,
            'auth_data' => $authData,
        ];
    }

    public static function decryptAuthData($jwt)
    {
        try {
            if (! Cache::has($jwt)) {
                $data = (array) JWT::decode($jwt, new Key(config('app.key'), 'HS256'));
                if (! self::checkSession($data['id'], $data['session'])) {
                    return false;
                }
                $user = User::select([
                    'id',
                    'email',
                    'is_admin',
                    'is_staff',
                ])
                    ->find($data['id']);
                if (! $user) {
                    return false;
                }
                Cache::put($jwt, $user->toArray(), 3600);
            }

            return Cache::get($jwt);
        } catch (\Exception $e) {
            return false;
        }
    }

    private static function checkSession($userId, $session): bool
    {
        $sessions = (array) Cache::get(CacheKey::get('USER_SESSIONS', $userId)) ?? [];
        if (! in_array($session, array_keys($sessions))) {
            return false;
        }

        return true;
    }

    private static function addSession($userId, $guid, $meta): void
    {
        $cacheKey = CacheKey::get('USER_SESSIONS', $userId);
        $sessions = (array) Cache::get($cacheKey, []);
        $sessions[$guid] = $meta;
        Cache::put($cacheKey, $sessions);
    }

    public function getSessions(): array
    {
        return (array) Cache::get(CacheKey::get('USER_SESSIONS', $this->user->id), []);
    }

    public function removeSession($sessionId): bool
    {
        $cacheKey = CacheKey::get('USER_SESSIONS', $this->user->id);
        $sessions = (array) Cache::get($cacheKey, []);
        unset($sessions[$sessionId]);
        if (! Cache::put(
            $cacheKey,
            $sessions
        )) {
            return false;
        }

        return true;
    }

    public function removeAllSession(): bool
    {
        $cacheKey = CacheKey::get('USER_SESSIONS', $this->user->id);

        return Cache::forget($cacheKey);
    }
}
