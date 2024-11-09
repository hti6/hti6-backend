<?php

namespace App\Http\Controllers\Map;

use App\Http\Controllers\Controller;
use App\Http\Requests\Map\MapRequest;
use App\Http\Resources\Camera\CameraGetResource;
use App\Http\Resources\DamageRequest\DamageRequestGetResource;
use App\Models\Camera;
use App\Models\DamageRequest;
use Illuminate\Http\JsonResponse;

final readonly class MapController extends Controller
{
    /**
     * @param MapRequest $request
     * @return JsonResponse
     */
    public function __invoke(MapRequest $request): JsonResponse
    {
        $dto = $request->validated();

        $map = DamageRequest::query();
        $cameras = Camera::query();

        if (isset($dto['date_from'])) {
            $map = $map->whereIn('created_at', [$dto['date_from'], $dto['date_to']]);
        }

        if (isset($dto['categories'])) {
            $map = $map->whereHas('categories', function ($query) use ($dto) {
                $query->whereIn('categories.name', $dto['categories']);
            });
        }

        if (isset($dto['users']) && !$dto['users']) {
            $map = $map->where('user_id', '=', null);
        }

        if (isset($dto['cameras']) && !$dto['cameras']) {
            $map = $map->where('camera_id', '=', null);
        }

        if (isset($dto['low_priority']) && !$dto['low_priority']) {
            $map = $map->where('priority', '!=', 'low');
        }

        if (isset($dto['middle_priority']) && !$dto['middle_priority']) {
            $map = $map->where('priority', '!=', 'middle');
        }

        if (isset($dto['high_priority']) && !$dto['high_priority']) {
            $map = $map->where('priority', '!=', 'low');
        }

        if (isset($dto['critical_priority']) && !$dto['critical_priority']) {
            $map = $map->where('priority', '!=', 'critical');
        }

        $map = $map->get();
        $cameras = $cameras->get();

        return $this->present(qck_response(['damages' => DamageRequestGetResource::collection($map), 'cameras' => CameraGetResource::collection($cameras)]));
    }
}
