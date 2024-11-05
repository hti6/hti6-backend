<?php

namespace App\Http\Controllers\Camera;

use App\Entities\IPCamera;
use App\Http\Controllers\Controller;
use App\Http\Requests\Camera\StoreRequest;
use App\Models\Camera;
use Clickbar\Magellan\Data\Geometries\Point;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    /**
     * @param StoreRequest $request
     * @return JsonResponse
     */
    public function __invoke(StoreRequest $request): JsonResponse
    {
        $dto = $request->validated();

        $camera = new IPCamera();

        $result = $camera->checkCamera($dto['url'], $dto['username'] ?? null, $dto['password'] ?? null, $dto['port'] ?? null);

        if ($result['success']) {
            Camera::create([
                'name' => $dto['name'],
                'url' => $dto['url'],
                'username' => $dto['username'] ?? null,
                'password' => $dto['password'] ?? null,
                'port' => $dto['port'] ?? null,
                'point' => Point::make($dto['latitude'], $dto['longitude'])
            ]);
        }

        return $this->present(qck_response());
    }
}
