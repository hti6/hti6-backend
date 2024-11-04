<?php

namespace App\Http\Resources\DamageRequest;

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
            'coordinates' => $this->point->x . ',' . $this->point->y,
            'created_at' => $this->created_at
        ];
    }
}
