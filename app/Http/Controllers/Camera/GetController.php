<?php

namespace App\Http\Controllers\Camera;

use App\Http\Controllers\Controller;
use App\Http\Resources\Camera\CameraGetResource;
use App\Models\Camera;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final readonly class GetController extends Controller
{
    /**
     * @param string $id
     * @return JsonResponse
     */
    public function __invoke(string $id): JsonResponse
    {
        $camera = Camera::findOrFail($id);

        return $this->present(qck_response(new CameraGetResource($camera)));
    }
}
