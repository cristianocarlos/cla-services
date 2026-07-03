<?php

namespace App\Http\Controllers;

use App\Http\Resources\JsonResponseResource;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthenticationController extends Controller
{
    public function token(): JsonResponse {
        $userModel = context('authApiModel');
        $accessToken = JWTAuth::fromUser($userModel);
        return response()->json(new JsonResponseResource(['token' => $accessToken]));
    }
}
