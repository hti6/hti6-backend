<?php

namespace App\Http\Controllers\Camera;

use App\Entities\IPCamera;
use App\Http\Controllers\Controller;
use App\Http\Requests\Camera\StoreRequest;
use App\Models\Camera;
use Clickbar\Magellan\Data\Geometries\Point;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Junges\Kafka\Facades\Kafka as KafkaFacade;
use Junges\Kafka\Message\Message;

final readonly class StoreController extends Controller
{
    /**
     * @param StoreRequest $request
     * @return JsonResponse
     */
    public function __invoke(StoreRequest $request): JsonResponse
    {
        $dto = $request->validated();

        $camera = new IPCamera();

        $result = $camera->checkCamera($dto['url'], $dto['username'] ?? null, $dto['password'] ?? null, $dto['port'] ?? null);

        if ($result['success']) {
            DB::transaction(function () use ($dto) {
                $camera = Camera::create([
                    'name' => $dto['name'],
                    'url' => $dto['url'],
                    'username' => $dto['username'] ?? null,
                    'password' => $dto['password'] ?? null,
                    'port' => $dto['port'] ?? null,
                    'point' => Point::make($dto['latitude'], $dto['longitude'])
                ]);

                $message = new Message(
                    body: [
                        'camera_id' => $camera->id,
                        'rtsp_url' => $camera->url
                    ]
                );
                KafkaFacade::publish(config('kafka.brokers'))
                    ->onTopic('cameras')
                    ->withMessage($message)
                    ->send();
            });
        }

        return $this->present(qck_response());
    }
}
