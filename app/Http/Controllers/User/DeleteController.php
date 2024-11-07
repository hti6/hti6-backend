<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final readonly class DeleteController extends Controller
{
    /**
     * @param string $id
     * @return JsonResponse
     */
    public function __invoke(string $id): JsonResponse
    {
        $user = User::findOrFail($id);

        $user->delete();

        return $this->present(qck_response());
    }
}
