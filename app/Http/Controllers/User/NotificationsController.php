<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\Notification\IndexResource;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function __invoke()
    {
        $user = getUser();

        $notifications = $user->notifications;

        foreach ($notifications as $notification) {
            $notification->update([
                'is_readed' => true
            ]);
        }

        return $this->present(qck_response(IndexResource::collection($notifications)));
    }
}
