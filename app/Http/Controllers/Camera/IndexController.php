<?php

namespace App\Http\Controllers\Camera;

use App\Http\Controllers\Controller;
use App\Http\Requests\Camera\IndexRequest;
use App\Http\Resources\Camera\CameraIndexResource;
use App\Models\Camera;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    /**
     * @param IndexRequest $request
     * @return JsonResponse
     */
    public function __invoke(IndexRequest $request): JsonResponse
    {
        $dto = $request->validated();

        $cameras = Camera::query();

        if (isset($dto['sort'])) {
            $cameras = $cameras->orderBy($dto['sort'], $dto['sort_order'] ?? 'DESC');
        }

        if (isset($dto['search'])) {
            $cameras = $cameras->where('name', 'ILIKE', '%' . $dto['search'] . '%');
        }

        $cameras = $cameras->paginate($dto['first'] ?? 100, page: $dto['page'] ?? 1);

        $meta = [
            'total' => $cameras->total(),
            'current_page' => $cameras->currentPage(),
            'per_page' => $cameras->perPage(),
        ];

        return $this->present(qck_response(CameraIndexResource::collection($cameras), meta: $meta));
    }
}
