<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final readonly class LogoutController extends Controller
{
    /**
     * @return JsonResponse
     * @throws AuthenticationException
     */
    public function __invoke(): JsonResponse
    {
        $result = getUser()->currentAccessToken()->delete();

        return $this->present(qck_response($result));
    }
}
