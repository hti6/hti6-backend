<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserGetResource;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final readonly class SelfController extends Controller
{
    /**
     * @return JsonResponse
     * @throws AuthenticationException
     */
    public function __invoke(): JsonResponse
    {
        $user = getUser();

        return $this->present(qck_response(new UserGetResource($user)));
    }
}
