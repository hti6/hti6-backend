<?php

use App\Models\Admin;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;

/**
 * @return User|Admin
 * @throws AuthenticationException
 */
function getUser(): User|Admin
{
    $user = Auth::guard('sanctum')->user();
    if ($user) {
        return $user;
    } else {
        throw new AuthenticationException();
    }
}


/**
 * @param mixed $data
 * @param string $message
 * @return array
 */
function qck_response(mixed $data = true, string $message = 'Success', ?array $meta = null): array
{
    return [
        'result' => $data,
        'message' => __($message),
        'meta' => $meta
    ];
}

/**
 * @param string $message
 * @return string
 */
function qck_error(string $message): string
{
    return __($message);
}
