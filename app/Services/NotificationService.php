<?php

namespace App\Services;

use App\Models\Admin;
use App\Models\Notification;
use App\Models\User;

final readonly class NotificationService
{
    /**
     * @param User|Admin $user
     * @param string $title
     * @param string $content
     * @return void
     */
    public function notify(User|Admin $user, string $title, string $content): void
    {
        Notification::create([
            'title' => $title,
            'content' => $content,
            'is_readed' => false,
            'userable_type' => get_class($user),
            'userable_id' => $user->id,
        ]);
    }
}
