<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * @mixin IdeHelperApiToken
 */
class ApiToken extends Authenticatable implements JWTSubject
{
    const string GUARD = 'api_token';

    protected $table = 'admin.api_token';
    protected $primaryKey = 'aptk_code';
    public $timestamps = false;
    protected $casts = [];
    protected $attributes = [];
    protected $fillable = [
        'aptk_toke',
        'aptk_name',
        'aptk_daho',
    ];
    protected $hidden = [
        'aptk_toke',
    ];

    public function getJWTIdentifier() {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array {
        return [];
    }

    public function generate(): array {
        $token = Str::random(80);
        $hashedToken = hash('sha256', $token);
        return [
            'hashedToken' => $hashedToken, // Token salvo no banco
            'token' => $token, // Token sem hash enviado ao usuário
        ];
    }
}
