<?php

namespace App\Http\Controllers\DamageRequest;

use App\Http\Controllers\Controller;
use App\Http\Requests\DamageRequest\StoreRequest;
use App\Models\DamageRequest;
use Clickbar\Magellan\Data\Geometries\Point;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Junges\Kafka\Message\Message;
use Junges\Kafka\Facades\Kafka as KafkaFacade;

class StoreController extends Controller
{
    /**
     * @param StoreRequest $request
     * @return JsonResponse
     * @throws AuthenticationException
     */
    public function __invoke(StoreRequest $request): JsonResponse
    {
        $dto = $request->validated();

        $user = getUser();

        DB::transaction(function () use ($user, $dto) {
            $damageRequest = DamageRequest::create([
                'point' => Point::make($dto['latitude'], $dto['longitude']),
                'photo_url' => $dto['photo_url'],
                'user_id' => $user->id,
            ]);

            $message = new Message(
                body: [
                    'damage_request_id' => $damageRequest->id,
                    'photo_url' => $damageRequest->photo_url
                ]
            );
            KafkaFacade::publish(config('kafka.brokers'))
                ->onTopic('images')
                ->withMessage($message)
                ->send();
        });

        return $this->present(qck_response());
    }
}
