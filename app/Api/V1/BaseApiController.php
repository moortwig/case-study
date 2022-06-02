<?php

namespace App\Api\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class BaseApiController extends BaseController
{

    public function badResponse(string $json): JsonResponse
    {
        return new JsonResponse($json, 400, [], 0, true);
    }

    public function okResponse(string $json): JsonResponse
    {
        return new JsonResponse($json, 200, [], 0, true);
    }
}
