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
        print("Sending request");
        $response = $request
            ->post(
                config('ai.AI_URL'),
                [
                    'files' => [$file_url],
                ]
            );
        print("Request sended status:" . $response->status());
        if ($response->status() == 200) {
            if (isset($damage_request_id)) {
                print("Finding damage_request");
                $damageRequest = DamageRequest::findOrFail($damage_request_id);
                print("Damage request finded");
                $response = $response->json();
                $damageRequest->update([
                    'priority' => $response[0]['type'] ?? 'middle',
                    'photo_url' => $response[0]['image_url'] ? 'https://cdn.indock.ru/images/' . $response[0]['image_url'] : $damageRequest->photo_url,
                ]);
                print("Damage request updated id:" . $damageRequest->id);
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
