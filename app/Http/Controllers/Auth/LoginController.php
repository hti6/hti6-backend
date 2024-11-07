<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\Custom\InvalidCredentialsException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

final readonly class LoginController extends Controller
{
    /**
     * @param LoginRequest $request
     * @return JsonResponse
     * @throws InvalidCredentialsException
     */
    public function __invoke(LoginRequest $request): JsonResponse
    {
        $dto = $request->validated();

        if (Auth::guard('admin')
            ->attempt(['login' => $dto['login'], 'password' => $dto['password']])) {
            $user = Auth::guard('admin')->user();

            $token = $user->createToken('auth-token', ['role:user','role:admin'])->plainTextToken;
        } else if (Auth::guard('web')
            ->attempt(['login' => $dto['login'], 'password' => $dto['password']])) {
            $user = Auth::user();

            $token = $user->createToken('auth-token', ['role:user'])->plainTextToken;
        } else {
            return $this->present(qck_response(false, "Invalid credentials"), Response::HTTP_UNAUTHORIZED);
        }

        return $this->present(qck_response(['token' => $token]));
    }
}
