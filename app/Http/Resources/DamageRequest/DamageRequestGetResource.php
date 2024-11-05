<?php

namespace App\Http\Resources\DamageRequest;

use App\Http\Resources\Camera\CameraGetResource;
use App\Http\Resources\User\UserGetResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DamageRequestGetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'priotity' => $this->priority,
            'latitude' => $this->point->getX(),
            'longitude' => $this->point->getY(),
            'created_at' => $this->created_at,
            'user' => new UserGetResource($this->user),
            'camera' => new CameraGetResource($this->camera),
            'type' => $this->getType()
        ];
    }
}
