<?php

namespace App\Services;

use App\Exceptions\Custom\AIServiceException;
use App\Models\Camera;
use App\Models\Category;
use App\Models\DamageRequest;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

final class AIService
{
    /**
     * @param $file_url
     * @param string $damage_request_id
     * @return void
     * @throws AIServiceException
     * @throws ConnectionException
     */
    public function request($file_url, string $damage_request_id): void
    {
        $request = Http::withHeaders(
            [
                'Accept' => 'application/json',
            ]
        );
        print("Sending request");
        $response = $request
            ->post(
                config('ai.AI_URL') . 'predict',
                [
                    'files' => [$file_url],
                ]
            );
        print("Request sended status:" . $response->status());
        if ($response->status() == 200) {
            print("Finding damage_request");
            $damageRequest = DamageRequest::findOrFail($damage_request_id);
            print("Damage request finded");
            $response = $response->json();
            $damageRequest->update([
                'priority' => $response[0]['type'] ?? 'middle',
                'photo_url' => $response[0]['image_url'] ? 'https://cdn.indock.ru/images/' . $response[0]['image_url'] : $damageRequest->photo_url,
            ]);
            if (isset($response[0]['predictions']) && is_array($response[0]['predictions'])) {
                $damageClasses = collect($response[0]['predictions'])
                    ->pluck('class')
                    ->unique()
                    ->values()
                    ->toArray();

                foreach ($damageClasses as $className) {
                    $category = Category::firstOrCreate(
                        ['name' => $className],[]
                    );

                    if (!$damageRequest->categories()->where('category_id', $category->id)->exists()) {
                        $damageRequest->categories()->attach($category->id);
                    }
                }
            } else {
                $damageRequest->delete();
            }
            print("Damage request updated id:" . $damageRequest->id);
        } else {
            throw new AIServiceException();
        }
    }

    /**
     * @param string $rtsp_url
     * @param string $camera_id
     * @return void
     * @throws AIServiceException
     * @throws ConnectionException
     */
    public function request_to_camera(string $rtsp_url, string $camera_id): void
    {
        $request = Http::withHeaders(
            [
                'Accept' => 'application/json',
            ]
        );
        print("Sending request");
        $response = $request
            ->post(
                config('ai.AI_URL') . 'rtsp',
                [
                    'rtsps' => [$rtsp_url],
                ]
            );
        if ($response->status() == 200) {
            print("Finding camera");
            $camera = Camera::findOrFail($camera_id);
            print("Camera finded");
            $response = $response->json();
            if (isset($response[0]['predictions']) && count($response[0]['predictions']) == 0) {
                print("No predictions");
                $camera->update([
                    'photo_url' => $response[0]['image_url'] ? 'https://cdn.indock.ru/images/' . $response[0]['image_url'] : null
                ]);
                return;
            }
            $damageRequest = DamageRequest::create([
                'priority' => $response[0]['type'] ?? 'middle',
                'photo_url' => $response[0]['image_url'] ? 'https://cdn.indock.ru/images/' . $response[0]['image_url'] : null,
                'camera_id' => $camera->id,
                'point' => $camera->point
            ]);
            if (isset($response[0]['predictions']) && is_array($response[0]['predictions'])) {
                $damageClasses = collect($response[0]['predictions'])
                    ->pluck('class')
                    ->unique()
                    ->values()
                    ->toArray();

                foreach ($damageClasses as $className) {
                    $category = Category::firstOrCreate(
                        ['name' => $className],[]
                    );

                    if (!$damageRequest->categories()->where('category_id', $category->id)->exists()) {
                        $damageRequest->categories()->attach($category->id);
                    }
                }
            }
            print("Damage request created id:" . $damageRequest->id);
            $camera->update([
                'photo_url' => $response[0]['image_url'] ? 'https://cdn.indock.ru/images/' . $response[0]['image_url'] : null
            ]);
            print("Camera updated");
        } else {
            throw new AIServiceException();
        }
    }
}
