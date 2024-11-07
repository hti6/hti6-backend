<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

final readonly class StoreController extends Controller
{
    /**
     * @param StoreRequest $request
     * @return JsonResponse
     */
    public function __invoke(StoreRequest $request): JsonResponse
    {
        $dto = $request->validated();

        User::create([
            'name' => $dto['name'],
            'login' => $dto['login'],
            'password' => Hash::make($dto['password']),
        ]);

        return $this->present(qck_response());
    }
}
