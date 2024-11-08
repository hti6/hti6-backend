<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\Notification\IndexResource;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final readonly class NotificationsController extends Controller
{
    /**
     * @return JsonResponse
     * @throws AuthenticationException
     */
    public function __invoke(): JsonResponse
    {
        $user = getUser();

        $notifications = $user->notifications()->get();

        foreach ($notifications as $notification) {
            $notification->update([
                'is_readed' => true
            ]);
        }

        return $this->present(qck_response(IndexResource::collection($notifications)));
    }
}
