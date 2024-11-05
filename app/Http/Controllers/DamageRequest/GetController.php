<?php

namespace App\Http\Controllers\DamageRequest;

use App\Http\Controllers\Controller;
use App\Http\Resources\DamageRequest\DamageRequestGetResource;
use App\Models\DamageRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetController extends Controller
{
    /**
     * @param string $id
     * @return JsonResponse
     */
    public function __invoke(string $id): JsonResponse
    {
        $damage_request = DamageRequest::findOrFail($id);

        return $this->present(qck_response(new DamageRequestGetResource($damage_request)));
    }
}
