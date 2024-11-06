<?php

namespace App\Console\Commands\Consumers;

use App\Services\AIService;
use Illuminate\Console\Command;
use Junges\Kafka\Contracts\ConsumerMessage;
use Junges\Kafka\Contracts\MessageConsumer;
use Junges\Kafka\Facades\Kafka;

class ImageConsumer extends Command
{
    protected $signature = 'consume:image';

    protected $description = 'Обрабатывает изображения';

    public function handle()
    {
        $consumer = Kafka::consumer(['images'])
            ->withBrokers(config('kafka.brokers'))
            ->withAutoCommit()
            ->withHandler(function (ConsumerMessage $message, MessageConsumer $consumer) {
                $aiService = new AIService();
                $message = $message->getBody();
                $aiService->request($message['file_url'], $message['damage_request_id']);
            })
            ->build();

        $consumer->consume();
    }

}
