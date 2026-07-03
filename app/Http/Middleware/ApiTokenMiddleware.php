<?php

namespace App\Http\Middleware;

use App\Http\Resources\JsonFeedbackResource;
use App\Models\ApiToken;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Context;
use Symfony\Component\HttpFoundation\Response;

class ApiTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response {
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json(new JsonFeedbackResource('Claritor Services api token required'), Response::HTTP_UNAUTHORIZED);
        }
        $model = ApiToken::where('aptk_toke', hash('sha256', $token))->first();
        if (!$model) {
            Context::add('authApiModel', null);
            return response()->json(new JsonFeedbackResource('Invalid ward api token' . hash('sha256', $token)), Response::HTTP_UNAUTHORIZED);
        }
        Context::add('authApiModel', $model);
        return $next($request);
    }
}
