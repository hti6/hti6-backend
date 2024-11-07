<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\IndexRequest;
use App\Http\Resources\User\UserGetResource;
use App\Models\User;
use Illuminate\Http\Request;

final readonly class IndexController extends Controller
{
    public function __invoke(IndexRequest $request)
    {
        $dto = $request->validated();

        $users = User::query();

        if (isset($dto['sort'])) {
            $users->orderBy($dto['sort'], $dto['sort_order'] ?? 'desc');
        }

        if (isset($dto['search'])) {
            $users->where('name', 'ILIKE', '%' . $dto['search'] . '%');
        }

        $users = $users->paginate($dto['first'] ?? 100, page: $dto['page'] ?? 1);

        $meta = [
            'total' => $users->total(),
            'current_page' => $users->currentPage(),
            'per_page' => $users->perPage(),
        ];

        return $this->present(qck_response(UserGetResource::collection($users), meta: $meta));
    }
}
