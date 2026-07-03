<?php

namespace App\Custom;

use App\DTOs\AuthUserDTO;
use App\Http\Resources\JsonFeedbackResource;
use App\Http\Resources\JsonResponseResource;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Cookie as SymfonyCookie;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Payload;

class JwtHelper
{
    private const string REFRESH_TOKEN_COOKIE_PATH = '/';
    private const int REFRESH_TOKEN_COOKIE_DURATION_IN_DAYS = 14;

    private static function setRefreshTokenCookie(?string $value, string $cookieName): SymfonyCookie {
        return cookie(
            name: $cookieName,
            value: $value,
            minutes: $value ? 60 * 24 * static::REFRESH_TOKEN_COOKIE_DURATION_IN_DAYS : -1,
            path: static::REFRESH_TOKEN_COOKIE_PATH,
            domain: config('session.refresh_token_domain'), // para poder acessar o tokem em outro subdomínio
            secure: config('session.secure', false),
            httpOnly: true,
            raw: false,
            sameSite: config('session.same_site', 'lax'),
        );
    }

    public static function refreshTokenGenerate(int $userId, string $cookieName): string {
        return JWTAuth::getJWTProvider()->encode([
            'iss' => config('app.url'),
            'iat' => now()->timestamp,
            'exp' => now()->addDays(static::REFRESH_TOKEN_COOKIE_DURATION_IN_DAYS)->timestamp,
            'sub' => $userId,
            'type' => $cookieName,
            'jti' => bin2hex(random_bytes(16)),
            'rot' => now()->timestamp,
        ]);
    }

    private static function refreshTokenValidate(string $refreshToken, string $cookieName): Payload {
        $payload = JWTAuth::setToken($refreshToken)->check(true);
        if (($payload->get('exp') ?: 0) < now()->timestamp) {
            throw new \Exception('Expired');
        }
        if ($payload->get('type') !== $cookieName) {
            throw new \Exception('Unknown');
        }
        $requiredKeys = ['iss', 'sub', 'jti', 'rot'];
        $requiredKeysOnToken = array_intersect(array_keys($payload->toArray()), $requiredKeys);
        if (count($requiredKeysOnToken) < count($requiredKeys)) {
            throw new \Exception('Missing keys');
        }
        return $payload;
    }

    public static function responseJsonWithExpiredCookie(string $message, string $cookieName, int $httpStatusCode = JsonResponse::HTTP_UNAUTHORIZED): JsonResponse {
        return response()->json(new JsonFeedbackResource(message: $message, success: false), $httpStatusCode)
            ->withCookie(static::setRefreshTokenCookie(null, $cookieName));
    }

    public static function responseJsonLogout(string $message, string $cookieName): JsonResponse {
        // JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(new JsonFeedbackResource($message))
            ->withCookie(static::setRefreshTokenCookie(null, $cookieName));
    }

    public static function responseJsonWithAccessTokenAndCookie(
        string $accessToken,
        string $refreshToken,
        string $cookieName,
        string $guard,
    ): JsonResponse {
        $content = [
            'access_token' => $accessToken,
            'token_type' => 'bearer',
            'expires_in' => auth($guard)->factory()->getTTL() * 60,
            'auth_user' => static::getAuthUser($accessToken),
        ];
        if (!app()->isProduction()) {
            $content = array_merge($content, [
                '__debug__new_refresh-token' => substr($refreshToken, -8),
                '__debug__old_refresh-token' => substr(request()->cookie($cookieName), -8),
            ]);
        }

        return response()->json(new JsonResponseResource($content))
            ->withCookie(static::setRefreshTokenCookie($refreshToken, $cookieName));
    }

    public static function getRefreshTokenPayload(string $cookieName): Payload {
        $refreshToken = request()->cookie($cookieName);
        if (!$refreshToken) {
            throw new \Exception('Refresh token not found');
        }
        try {
            $payload = static::refreshTokenValidate($refreshToken, $cookieName);
        } catch (\Exception $e) {
            throw new \Exception('Invalid refresh token. ' . $e->getMessage());
        }
        return $payload;
    }

    private static function getAccessTokenPayload(?string $accessToken = null): ?Payload {
        try {
            return JWTAuth::setToken($accessToken ?: request()->bearerToken())->check(true) ?: null;
        } catch (\Exception $_e) {
            return null;
        }
    }

    public static function getAuthUser(?string $accessToken = null): ?AuthUserDTO {
        $payload = static::getAccessTokenPayload($accessToken);
        if (empty($payload)) return null;
        return new AuthUserDTO(
            id: $payload->get('sub'),
            name: $payload->get('name'),
            email: $payload->get('email'),
            is_root: $payload->get('is_root'),
            login_count: $payload->get('login_count'),
        );
    }
}
