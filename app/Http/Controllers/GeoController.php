<?php

namespace App\Http\Controllers;

use App\Enums\BoolCharEnum;
use App\Http\Resources\JsonResponseResource;
use App\Models\Country;
use App\Queries\CityQuery;
use App\Queries\CountryQuery;
use App\Rules\PtBrZipCodeRule;
use App\Services\ViaCepService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rules\Enum;

class GeoController
{
    public function address(ViaCepService $viaCepService): JsonResponse {
        request()->validate([
            'zipCode' => new PtBrZipCodeRule,
        ]);
        $responseData = $viaCepService->fetch(request()->query('zipCode'));
        return response()->json(new JsonResponseResource(CityQuery::resolveViaCepJsonResponse($responseData)));
    }

    public function city(): JsonResponse {
        request()->validate([
            'term' => 'string',
            'limit' => 'integer',
            'offset' => 'integer',
            'countryId' => 'string|size:3',
        ]);
        return response()->json(new JsonResponseResource(CityQuery::getSuggestOptions(
            term: request()->query('term'),
            limit: request()->query('limit'),
            offset: request()->query('offset'),
            countryId: request()->query('countryId') ?: Country::HOME,
        )));
    }

    public function country(): JsonResponse {
        request()->validate([
            'term' => 'string',
            'limit' => 'integer',
            'offset' => 'integer',
            'withCity' => new Enum(BoolCharEnum::class),
        ]);
        return response()->json(new JsonResponseResource(CountryQuery::getSuggestOptions(
            term: request()->query('term'),
            limit: request()->query('limit'),
            offset: request()->query('offset'),
            withCity: request()->query('withCity') ?: BoolCharEnum::FALSE->value,
        )));
    }
}
