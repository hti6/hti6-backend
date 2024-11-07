<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateSelfRequest;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;

final readonly class UpdateSelfController extends Controller
{
    /**
     * @param UpdateSelfRequest $request
     * @return JsonResponse
     * @throws AuthenticationException
     */
    public function __invoke(UpdateSelfRequest $request): JsonResponse
    {
        $dto = $request->validated();

        $user = getUser();

        $user->update($dto);

        return $this->present(qck_response());
    }
}
