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
    public function request($file_url, ?DamageRequest $damageRequest = null, ?Camera $camera = null): void
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

        if ($response->successful()) {
            if (isset($damageRequest)) {
                $damageRequest->update([
                    'priority' => $response->type ?? 'middle',
                    'photo_url' => $response->image_url ?? $damageRequest->photo_url,
                ]);
            } else {
                DamageRequest::create([
                    'point' => $camera?->point,
                    'priority' => $response->type ?? 'middle',
                    'photo_url' => $response->image_url ?? null,
                    'camera_id' => $camera?->id
                ]);
            }
        } else {
            throw new AIServiceException();
        }
    }
}
