<?php

namespace App\Http\Controllers\DamageRequest;

use App\Http\Controllers\Controller;
use App\Http\Requests\DamageRequest\IndexRequest;
use App\Http\Resources\DamageRequest\DamageRequestGetResource;
use App\Http\Resources\DamageRequest\DamageRequestIndexResource;
use App\Models\DamageRequest;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final readonly class IndexController extends Controller
{
    /**
     * @param IndexRequest $request
     * @return JsonResponse
     * @throws AuthenticationException
     */
    public function __invoke(IndexRequest $request): JsonResponse
    {
        $dto = $request->validated();

        $damageRequests = DamageRequest::query();

        if (isset($dto['search'])) {
            $damageRequests = $damageRequests->where('id', 'ILIKE', '%' . $dto['search'] . '%');
        }

        if (isset($dto['filter'])) {
            match ($dto['filter']) {
                'users' => $damageRequests = $damageRequests->where('camera_id','=',null),
                'cameras' => $damageRequests = $damageRequests->where('user_id','=', null)
            };
        }

        if (isset($dto['sort'])) {
            $damageRequests = $damageRequests->orderBy($dto['sort'], $dto['order'] ?? 'DESC');
        } else {
            $damageRequests = $damageRequests->orderBy('created_at', 'desc');
        }

        $damageRequests = $damageRequests->paginate($dto['first'] ?? 100, page: $dto['page'] ?? 1);

        $meta = [
            'total' => $damageRequests->total(),
            'current_page' => $damageRequests->currentPage(),
            'per_page' => $damageRequests->perPage(),
        ];

        return $this->present(qck_response(DamageRequestGetResource::collection($damageRequests), meta: $meta));
    }
}
