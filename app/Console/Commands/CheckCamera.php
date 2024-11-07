<?php

namespace App\Console\Commands;

use App\Models\Camera;
use Illuminate\Console\Command;
use Junges\Kafka\Facades\Kafka as KafkaFacade;
use Junges\Kafka\Message\Message;

class CheckCamera extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-camera';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks cameras';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        foreach (Camera::all() as $camera) {
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
        }
    }
}
