<?php

namespace App\Http\Controllers\DamageRequest;

use App\Http\Controllers\Controller;
use App\Http\Resources\DamageRequest\DamageRequestIndexResource;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    /**
     * @return JsonResponse
     * @throws AuthenticationException
     */
    public function __invoke(): JsonResponse
    {
        $user = getUser();

        $damageRequests = $user->damageRequests;

        return $this->present(qck_response(DamageRequestIndexResource::collection($damageRequests)));
    }
}
