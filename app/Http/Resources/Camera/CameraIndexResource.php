<?php

namespace App\Http\Resources\Camera;

use App\Http\Resources\CameraHistory\CameraHistoryIndexResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CameraIndexResource extends JsonResource
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
            'name' => $this->name,
            'url' => $this->url,
            'latitude' => $this->point->getX(),
            'longitude' => $this->point->getY(),
            'created_at' => $this->created_at,
            'photo_url' => $this->photo_url,
            'history' => CameraHistoryIndexResource::collection($this->histories)
        ];
    }
}
