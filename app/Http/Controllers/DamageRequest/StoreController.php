<?php

namespace App\Http\Controllers\DamageRequest;

use App\Http\Controllers\Controller;
use App\Http\Requests\DamageRequest\StoreRequest;
use App\Models\DamageRequest;
use Clickbar\Magellan\Data\Geometries\Point;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    /**
     * @param StoreRequest $request
     * @return JsonResponse
     * @throws AuthenticationException
     */
    public function __invoke(StoreRequest $request): JsonResponse
    {
        $dto = $request->validated();

        $user = getUser();

        DamageRequest::create([
            'point' => Point::make($dto['latitude'], $dto['longitude']),
            'user_id' => $user->id,
        ]);

        return $this->present(qck_response());
    }
}
