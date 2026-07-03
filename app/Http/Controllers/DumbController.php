<?php

namespace App\Http\Controllers;

use App\Http\Resources\JsonResponseResource;
use Illuminate\Http\JsonResponse;

class DumbController extends Controller
{
    public function stuff(): JsonResponse {
        return response()->json(new JsonResponseResource(['agorasim' => 'não']));
    }
}
