<?php

namespace App\Http\Resources\DamageRequest;

use App\Http\Resources\Category\CategoryIndexResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DamageRequestIndexResource extends JsonResource
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
            'categories' => CategoryIndexResource::collection($this->categories),
            'type' => $this->getType()
        ];
    }
}
