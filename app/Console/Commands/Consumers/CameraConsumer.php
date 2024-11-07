<?php

namespace App\Console\Commands\Consumers;

use App\Services\AIService;
use Illuminate\Console\Command;
use Junges\Kafka\Contracts\ConsumerMessage;
use Junges\Kafka\Contracts\MessageConsumer;
use Junges\Kafka\Facades\Kafka;

class CameraConsumer extends Command
{
    protected $signature = 'consume:camera';

    protected $description = 'Обрабатывает камеры';

    public function handle()
    {
        $consumer = Kafka::consumer(['cameras'])
            ->withBrokers(config('kafka.brokers'))
            ->withAutoCommit()
            ->withHandler(function (ConsumerMessage $message, MessageConsumer $consumer) {
                $aiService = new AIService();
                $message = $message->getBody();
                $aiService->request_to_camera($message['rtsp_url'], $message['camera_url']);
            })
            ->build();

        $consumer->consume();
    }

}
