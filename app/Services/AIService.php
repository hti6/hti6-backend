<?php

namespace App\Services;

use App\Exceptions\Custom\AIServiceException;
use App\Models\Camera;
use App\Models\DamageRequest;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

final class AIService
{
    /**
     * @throws ConnectionException|AIServiceException
     */
    public function request($file_url, ?string $damage_request_id = null, ?string $camera_id = null): void
    {
        $request = Http::withHeaders(
            [
                'Accept' => 'application/json',
            ]
        );

        $response = $request
            ->post(
                config('ai.AI_URL'),
                [
                    'files' => [$file_url],
                ]
            );

        if ($response->status() == 200) {
            if (isset($damage_request_id)) {
                $damageRequest = DamageRequest::findOrFail($damage_request_id);
                $damageRequest->update([
                    'priority' => $response->type ?? 'middle',
                    'photo_url' => $response->image_url ?? $damageRequest->photo_url,
                ]);
            } else {
                $camera = Camera::findOrFail($camera_id);
                DamageRequest::create([
                    'point' => $camera->point,
                    'priority' => $response->type ?? 'middle',
                    'photo_url' => $response->image_url ?? null,
                    'camera_id' => $camera->id
                ]);
            }
        } else {
            throw new AIServiceException();
        }
    }
}
