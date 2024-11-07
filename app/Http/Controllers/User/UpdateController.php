<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final readonly class UpdateController extends Controller
{
    /**
     * @param UpdateRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function __invoke(UpdateRequest $request, string $id)
    {
        $dto = $request->validated();

        $user = User::findOrFail($id);

        $user->update($dto);

        return $this->present(qck_response());
    }
}
