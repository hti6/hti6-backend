<?php

namespace App\Console\Commands\Consumers;

use App\Services\AIService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Junges\Kafka\Contracts\ConsumerMessage;
use Junges\Kafka\Contracts\MessageConsumer;
use Junges\Kafka\Facades\Kafka;
use Throwable;

class ImageConsumer extends Command
{
    protected $signature = 'consume:image';

    protected $description = 'Обрабатывает изображения';

    private const MAX_RETRIES = 3;
    private const RETRY_DELAY = 5;

    public function handle()
    {
        $consumer = Kafka::consumer(['images'])
            ->withBrokers(config('kafka.brokers'))
            ->withAutoCommit()
            ->withHandler(function (ConsumerMessage $message, MessageConsumer $consumer) {
                $retryCount = 0;
                $success = false;

                while (!$success && $retryCount < self::MAX_RETRIES) {
                    try {
                        $messageBody = $message->getBody();
                        $aiService = new AIService();

                        $this->info("Попытка #" . ($retryCount + 1) . " обработки сообщения");

                        $aiService->request(
                            $messageBody['photo_url'],
                            $messageBody['damage_request_id']
                        );

                        $success = true;
                        $this->info("Сообщение успешно обработано");

                    } catch (Throwable $e) {
                        $retryCount++;

                        Log::error("Ошибка при обработке сообщения: " . $e->getMessage(), [
                            'retry_count' => $retryCount,
                            'message_body' => $messageBody ?? null,
                            'exception' => $e
                        ]);

                        if ($retryCount < self::MAX_RETRIES) {
                            $this->warn("Повторная попытка через " . self::RETRY_DELAY . " секунд...");
                            sleep(self::RETRY_DELAY);
                        } else {
                            $this->error("Превышено максимальное количество попыток. Сообщение будет пропущено.");

                            $this->handleFailedMessage($messageBody ?? null, $e);
                        }
                    }
                }
            })
            ->build();

        $consumer->consume();
    }

}
